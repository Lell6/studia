<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;
use PDOException;

class PositionRepository {
    private $queryExecutor;
    private const QUERY_INSERT_POSITION = "INSERT INTO Zawód_Pracownika (
                                            Zawód, Płaca_Etat
                                         ) VALUES (
                                            :position, :payment
                                         );";
    private const QUERY_SELECT_BY_ID = "SELECT 
                                            Id, Zawód, Płaca_Etat
                                        FROM 
                                            Zawód_Pracownika
                                        WHERE 
                                            Id = :id;";
    private const QUERY_SELECT_ALL = "SELECT 
                                            Id, Zawód, Płaca_Etat
                                        FROM 
                                            Zawód_Pracownika";

    private const QUERY_DELETE_POSITION = "DELETE FROM Zawód_Pracownika WHERE Id = :id";
    private const QUERY_UPDATE_POSITION = "UPDATE Zawód_Pracownika
                  SET Zawód = :position, 
                      Płaca_Etat = :payment                    
                  WHERE Id = :id";

    public function __construct() {
        $this->queryExecutor = new DatabaseQueryExecutor();
    }

    public function getNewPositionId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function getPositionById($id) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_ID, [
            ':id' => $id
        ]);

        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Pozycja z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getAllPositions() {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_ALL, []);
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function addNewPosition($inputs) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_INSERT_POSITION, [
            ':position' => $inputs['position'],
            ':payment' => $inputs['payment']    
        ]);
    }

    public function deletePosition($id) {
        $result = $this->getPositionById($id);        
        if ($result['success'] == true && $result['record'] == null) {
            return [
                'success' => false,
                'message' => 'Zawód o takim ID nie istnieje'
            ];
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_POSITION, [
            ':id' => $id
        ]);
    }

    public function updatePosition($id, $inputs) {
        $result = $this->getPositionById($id);        
        if ($result['success'] == true && $result['record'] == null) {
            return [
                'success' => false,
                'message' => 'Zawód o takim ID nie istnieje'
            ];
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_POSITION, [
            ':position' => $inputs['position'],
            ':payment' => $inputs['payment'],
            ':id' => $id
        ]);
    }
}