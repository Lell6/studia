<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;
use PDOException;

class LecturerRepository {
    private $queryExecutor;
    private const QUERY_INSERT_LECTURER = "INSERT INTO [PlanetariumDB].dbo.Wykładowca (
                                            Imię, Nazwisko, Email, Numer_Komórkowy,
                                            Liczba_Przeprowadzonych_Wykładów, Płaca_Za_Wykład
                                         ) VALUES (
                                            :name, :surname, :email, :phoneNumber,
                                            :lecturesMade, :payment
                                         );";
    private const QUERY_SELECT_BY_ID = "SELECT 
                                            Imię, Nazwisko, Email, Numer_Komórkowy,
                                            Liczba_Przeprowadzonych_Wykładów, Płaca_Za_Wykład
                                        FROM 
                                            Wykładowca
                                        WHERE 
                                            Id = :lecturerId;";
    private const QUERY_SELECT_ALL = "SELECT 
                                            Id, Imię, Nazwisko, Email, Numer_Komórkowy,
                                            Liczba_Przeprowadzonych_Wykładów, Płaca_Za_Wykład
                                        FROM 
                                            Wykładowca;";

    private const QUERY_DELETE_LECTURER = "DELETE FROM [PlanetariumDB].dbo.Wykładowca WHERE Id = :lecturerId";
    private const QUERY_UPDATE_LECTURER = "UPDATE [PlanetariumDB].dbo.Wykładowca
                  SET Imię = :name, 
                      Nazwisko = :surname, 
                      Email = :email, 
                      Numer_Komórkowy = :phoneNumber,
                      Liczba_Przeprowadzonych_Wykładów = :lecturesMade,
                      Płaca_Za_Wykład = :payment                      
                  WHERE Id = :lecturerId";


    public function __construct() {
        $this->queryExecutor = new DatabaseQueryExecutor();
    }

    public function getNewLecturerId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function getLecturerById($id) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_ID, [
            'lecturerId' => $id
        ]);

        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Wykładowca z takim ID nie istnieje"
            ];
        }
        
        return $result;
    }

    public function getAllLecturers() {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_ALL, []);
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function addNewLecturer($inputs) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_INSERT_LECTURER, [
            ':name' => $inputs['name'],
            ':surname' => $inputs['surname'],
            ':email' => $inputs['email'],
            ':phoneNumber' => $inputs['phone'],
            ':lecturesMade' => $inputs['lecturesMade'],
            ':payment' => $inputs['payment']            
        ]);
    }

    public function deleteLecturer($lecturerId) {
        $result = $this->getLecturerById($lecturerId);
        if (!$result['success']) {
            return $result;
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_LECTURER, [
            'lecturerId' => $lecturerId
        ]);
    }

    public function updateLecturer($lecturerId, $inputs) {  
        $result = $this->getLecturerById($lecturerId);
        if (!$result['success']) {
            return $result;
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_LECTURER, [
            ':name' => $inputs['name'],
            ':surname' => $inputs['surname'],
            ':email' => $inputs['email'],
            ':phoneNumber' => $inputs['phone'],
            ':lecturesMade' => $inputs['lecturesMade'],
            ':payment' => $inputs['payment'],
            ':lecturerId' => $lecturerId
        ]);
    }
}