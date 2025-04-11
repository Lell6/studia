<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;

class WorkerRepository {
    private $queryExecutor;
    private const QUERY_INSERT_WORKER = "INSERT INTO Pracownik (
                    Imię, Nazwisko, Email, Numer_Komórkowy,
                    Miasto, Ulica, Numer_Domu, Numer_Mieszkania,
                    Zawód, Czas_Pracy, Użytkownik
                ) VALUES (
                    :name, :surname, :email, :phoneNumber,
                    :city, :street, :houseNumber, :apartmentNumber,
                    :position, :workhour, :userId
                )";
    private const QUERY_SELECT_BY_USER_ID = "SELECT 
                                                P.Imię, P.Nazwisko, P.Email, P.Numer_Komórkowy,
                                                P.Miasto, P.Ulica, P.Numer_Domu, P.Numer_Mieszkania,
                                                ZP.Zawód AS Zawód, 
                                                CP.Okres_godziny AS Czas_Pracy,
                                                ZP.Płaca_Etat AS Płaca,
                                                P.Użytkownik,
												Użytkownik.Przywileje
                                            FROM 
                                                Pracownik AS P
                                            INNER JOIN 
                                                Zawód_Pracownika AS ZP ON P.Zawód = ZP.Id
                                            INNER JOIN 
                                                Czas_Pracy AS CP ON P.Czas_Pracy = CP.Id
                                            INNER JOIN
                                                Użytkownik ON P.Użytkownik = Użytkownik.Id
                                            WHERE 
                                                P.Użytkownik = :userId;";
    private const QUERY_SELECT_ALL = "SELECT 
                                            [User].Login AS Login,
											P.Użytkownik AS Id_Użytkownika,
                                            [User].Przywileje AS Przywileje,
                                            P.Imię, P.Nazwisko, P.Email, P.Numer_Komórkowy,
                                            P.Miasto, P.Ulica, P.Numer_Domu, P.Numer_Mieszkania,
                                            ZP.Zawód AS Zawód,
                                            ZP.Płaca_Etat AS Płaca,
                                            CP.Okres_godziny AS Czas_Pracy
                                        FROM 
                                            Pracownik AS P
                                        LEFT JOIN 
                                            Zawód_Pracownika AS ZP ON P.Zawód = ZP.Id
                                        LEFT JOIN 
                                            Czas_Pracy AS CP ON P.Czas_Pracy = CP.Id
                                        INNER JOIN
                                            Użytkownik AS [User] ON P.Użytkownik = [User].Id";

    private const QUERY_SELECT_BY_PRIVILEGE = "SELECT [User].Login AS Login,
                                        P.Użytkownik AS Id_Użytkownika,
                                        [User].Przywileje AS Przywileje
                                        FROM 
                                            Pracownik AS P
                                        INNER JOIN
                                            Użytkownik AS [User] ON P.Użytkownik = [User].Id
                                        WHERE Przywileje = :privilege";

    private const QUERY_DELETE_WORKER = "DELETE FROM Pracownik WHERE Użytkownik = :userId";
    private const QUERY_UPDATE_WORKER_ADMIN = "UPDATE Pracownik 
                  SET Imię = :userName, 
                      Nazwisko = :userSurname, 
                      Email = :userEmail, 
                      Numer_Komórkowy = :userPhone, 
                      Miasto = :userCity, 
                      Ulica = :userStreet, 
                      Numer_Domu = :userHouseNumber, 
                      Numer_Mieszkania = :userApartmentNumber, 
                      Zawód = :userPosition, 
                      Czas_Pracy = :userWorkhour
                  WHERE Użytkownik = :id";
    private const QUERY_UPDATE_WORKER = "UPDATE Pracownik 
                  SET Imię = :userName, 
                      Nazwisko = :userSurname, 
                      Email = :userEmail, 
                      Numer_Komórkowy = :userPhone, 
                      Miasto = :userCity, 
                      Ulica = :userStreet, 
                      Numer_Domu = :userHouseNumber, 
                      Numer_Mieszkania = :userApartmentNumber
                  WHERE Użytkownik = :id";

    public function __construct($queryExecutor = null) {
        $this->queryExecutor = ($queryExecutor != null) ? $queryExecutor : new DatabaseQueryExecutor();
    }

    public function getNewWorkerId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function getAllWorkers() {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_ALL, []);
        if ($result['success']) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function getWorkerByUserId($userId) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_USER_ID, [
            'userId' => $userId
        ]);

        if (!$result['success']) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Pracownik z takim ID uzytkownika nie istnieje"
            ];
        }

        return $result;
    }

    public function getWorkersByPrivilege($privilege) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_PRIVILEGE, [
            'privilege' => $privilege
        ]); 
        if ($result['success']) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function getWorkHours() {
        $query = "SELECT * FROM Czas_Pracy";
        $result = $this->queryExecutor->executePreparedQuery($query, []);
        if ($result['success']) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function getPosition() {
        $query = "SELECT * FROM Zawód_Pracownika";
        $result = $this->queryExecutor->executePreparedQuery($query, []);
        if ($result['success']) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function addNewWorker($userId, $inputs) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_INSERT_WORKER, [
            ':name' => $inputs['name'],
            ':surname' => $inputs['surname'],
            ':email' => $inputs['email'],
            ':phoneNumber' => $inputs['phone'],
            ':city' => $inputs['city'],
            ':street' => $inputs['street'],
            ':houseNumber' => $inputs['house'],
            ':apartmentNumber' => $inputs['apartment'],
            ':position' => $inputs['position'],
            ':workhour' => $inputs['workhour'],
            ':userId' => $userId
        ]);
    }

    public function deleteWorker($userId) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_WORKER, [
            'userId' => $userId
        ]);
    }

    public function updateWorker($userid, $inputs) {        
        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_WORKER_ADMIN, [
            'userName' => $inputs['name'],
            'userSurname' => $inputs['surname'],
            'userEmail' => $inputs['email'],
            'userPhone' => $inputs['phone'],
            'userCity' => $inputs['city'],
            'userStreet' => $inputs['street'],
            'userHouseNumber' => $inputs['house'],
            'userApartmentNumber' => $inputs['apartment'],
            'userPosition' => $inputs['position'],
            'userWorkhour' => $inputs['workhour'],
            'id' => $userid
        ]);
    }
}