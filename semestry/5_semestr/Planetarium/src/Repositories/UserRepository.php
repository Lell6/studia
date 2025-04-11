<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;
use PDOException;
use Exception;

class UserRepository {
    private $queryExecutor;
    private $clientRepository;
    private $workerRepository;

    private const QUERY_SELECT_BY_LOGIN = "SELECT * FROM Użytkownik WHERE Login = :userLogin";
    private const QUERY_SELECT_BY_ID = "SELECT * FROM Użytkownik WHERE Id = :id";
    private const QUERY_INSERT_USER = "INSERT INTO Użytkownik (Login, Hasło_Hash, Przywileje) VALUES (:userLogin, :userPassword, :privileges)";
    private const QUERY_UPDATE_USER = "UPDATE Użytkownik SET Przywileje = :newPrivilege, Hasło_Hash = :newPassword WHERE Id = :id";
    private const QUERY_DELETE_USER = "DELETE FROM Użytkownik WHERE Id = :id";
    private const QUERY_UPDATE_PASSWORD = "UPDATE Użytkownik SET Hasło_Hash = :newPassword WHERE Id = :id";

    public function __construct() {
        $this->queryExecutor = new DatabaseQueryExecutor();
        $this->clientRepository = new ClientRepository($this->queryExecutor);
        $this->workerRepository = new WorkerRepository($this->queryExecutor);
    }

    public function getUserById($id) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_ID, [
            'id' => $id
        ]);

        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Użytkownik z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getUserByLogin($login) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_LOGIN, [
            'userLogin' => $login
        ]);

        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] != null) {
            return [
                'success' => false,
                'message' => "Użytkownik z takim loginem już istnieje"
            ];
        }

        return $result;
    }

    public function isUserExist($login) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_LOGIN, [
            'userLogin' => $login
        ]);

        if (!$result['success']) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        $result['success'] = ($result['record'] != null);

        return $result;
    }

    public function getNewUserId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function addNewUser($login, $password, $privilege) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_INSERT_USER, [
            'userLogin' => $login,
            'userPassword' => $password,
            'privileges' => $privilege
        ]);
    }

    public function updateUser($id, $newPassword) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_USER, [            
            'id' => $id
        ]);
    }

    public function deleteUser($id) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_USER, [
            'id' => $id
        ]);
    }

    private function executeTransaction($transactionCallback)
    {
        try {
            $this->queryExecutor->startTransaction();
            $result = $transactionCallback();
            $this->queryExecutor->commitTransaction();
            return $result;
        } catch (Exception $e) {
            $this->queryExecutor->rollBackTransaction();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function performLogin($login, $password) {
        return $this->executeTransaction(function() use ($login, $password) {
            $result = $this->isUserExist($login);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $passwordVerif = password_verify($password, $result['record']['Hasło_Hash']);
            if (!$passwordVerif) {
                throw new Exception("Hasz nie pasuje do inputu");
            }

            return $result;
        });
    }

    public function performRegister($accessHandler, $inputValues) {  
        return $this->executeTransaction(function() use ($accessHandler, $inputValues) {
            $result = $this->getUserByLogin($inputValues['login']);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $result = $this->addNewUser($inputValues['login'], $inputValues['password'], $inputValues['privilege']);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $newUserId = $this->getNewUserId();

            if ($accessHandler->isNoneLogged()) {
                $result = $this->clientRepository->addNewClient($newUserId, $inputValues['name'], $inputValues['surname'], $inputValues['email'], $inputValues['phone']);
                $result['message'] = "Dodawanie klienta: " . $result['message'];
            } else if ($accessHandler->isAdminLogged() || $accessHandler->isWorkerLogged()){
                $result = $this->workerRepository->addNewWorker($newUserId, $inputValues);
                $result['message'] = "Dodawanie pracownika: " . $result['message'];
            }
            
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            return $result;
        }); 
    }

    public function performUserUpdate($accessHandler, $userId, $inputValues) {
        if ($accessHandler->isClientLogged()) {
            $result = $this->clientRepository->updateClient($userId, $inputValues);
            $result['message'] = "Zmiana klienta: " . $result['message'];
        } else if ($accessHandler->isWorkerLogged() || $accessHandler->isAdminLogged()) {
            $result = $this->workerRepository->updateWorker($userId, $inputValues);
            $result['message'] = "Zmiana pracownika: " . $result['message'];
        }
        
        return $result;
    }

    public function performUserDelete($accessHandler, $userId) {
        return $this->executeTransaction(function() use ($accessHandler, $userId) {
            $result = $this->getUserById($userId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            if ($accessHandler->isClientLogged()) {
                $result = $this->clientRepository->getClientByUserId($userId);
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }

                $result = $this->clientRepository->deleteClient($userId);
            } else if ($accessHandler->isWorkerLogged() || $accessHandler->isAdminLogged()) {
                $result = $this->workerRepository->getWorkerByUserId($userId);
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }

                $worker = $result['record'];
                if ($worker['Przywileje'] == 4) {
                    $result = $this->workerRepository->getWorkersByPrivilege(4);
                    if (empty($result['records']) || count($result['records']) == 1 ) {
                        throw new Exception("Nie można usunać administratora - jest jedyny w systemie");
                    }
                }

                $result = $this->workerRepository->deleteWorker($userId);
            }

            if ($result['success'] !== true) {
                throw new Exception($result['message']);
            }
    
            $result = $this->deleteUser($userId);
            if ($result['success'] !== true) {
                throw new Exception($result['message']);
            }

            return $result;
        });
    }

    public function performPasswordUpdate($userId, $inputs) {
        $result = $this->getUserById($userId);
        if (!$result['success']) {
            return $result;
        }

        $userPassword = $result['record']['Hasło_Hash'];
        $passwordVerif = password_verify($inputs['oldPassword'], $userPassword);
        if (!$passwordVerif) {
            return [
                'success' => false,
                'message' => 'Nieprawidłowe stare hasło'
            ];
        }
        if ($inputs['newPassword'] != $inputs['newPasswordRepeat']) {
            return [
                'success' => false,
                'message' => 'Nowe i powtórzone hasło musi być takie same'
            ];
        }

        $inputs['newPassword'] = password_hash($inputs['newPassword'], PASSWORD_DEFAULT);
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_PASSWORD, [
            'newPassword' => $inputs['newPassword'],
            'id' => $userId
        ]);
        if (!$result['success']) {
            return $result;
        }

        return $result;
    }
}