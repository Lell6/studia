<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;

class ClientRepository {
    private $queryExecutor;

    private const QUERY_INSERT_CLIENT = "INSERT INTO Klient (Imię, Nazwisko, Email, Numer_Komórkowy, Użytkownik) VALUES (:name, :surname, :email, :phone, :userId)";
    private const QUERY_SELECT_BY_USER_ID = "SELECT * FROM Klient WHERE Użytkownik = :userId";
    private const QUERY_DELETE_CLIENT = "DELETE FROM Klient WHERE Użytkownik = :userId";
    private const QUERY_UPDATE_CLIENT = "UPDATE Klient SET Imię = :userName, Nazwisko = :userSurname, Email = :userEmail, Numer_Komórkowy = :userPhone WHERE Użytkownik = :id";
    private const QUERY_SELECT_ALL = "SELECT C.Id, C.Imię, C.Nazwisko, C.Email, C.Numer_Komórkowy, Użytkownik.Login FROM Klient AS C INNER JOIN Użytkownik ON C.Użytkownik = Użytkownik.Id";

    public function __construct($queryExecutor = null) {
        $this->queryExecutor = ($queryExecutor != null) ? $queryExecutor : new DatabaseQueryExecutor();
    }

    public function getNewClientId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function getClientByUserId($userId) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_USER_ID, [
            'userId' => $userId
        ]);

        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Klient z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getAllClients() {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_ALL, []);
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }


    public function addNewClient($userId, $name, $surname, $email, $phone) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_INSERT_CLIENT, [
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'phone' => $phone,
            'userId' => $userId
        ]);
    }

    public function deleteClient($clientId) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_CLIENT, [
            'userId' => $clientId
        ]);
    }

    public function updateClient($userid, $inputs) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_CLIENT, [
            'userName' => $inputs['name'],
            'userSurname' => $inputs['surname'],
            'userEmail' => $inputs['email'],
            'userPhone' => $inputs['phone'],
            'id' => $userid
        ]);
    }
}