<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;
use PDOException;

class InstrumentRepository {
    private $queryExecutor;
    private const QUERY_INSERT_INSTRUMENT = "INSERT INTO Sprzęt (
                                            Stan, Nazwa
                                         ) VALUES (
                                            :state, :name
                                         );";
    private const QUERY_SELECT_BY_ID = "SELECT 
                                            Sprzęt.Id, Sprzęt.Nazwa, Sprzęt.Stan
                                        FROM 
                                            Sprzęt
                                        WHERE 
                                            Sprzęt.Id = :id;";
    private const QUERY_SELECT_ALL = "SELECT 
                                            Sprzęt.Id, Sprzęt.Nazwa, Sprzęt.Stan
                                        FROM 
                                            Sprzęt;";

    private const QUERY_DELETE_INSTRUMENT = "DELETE FROM Sprzęt WHERE Id = :id";
    private const QUERY_UPDATE_INSTRUMENT_NAME = "UPDATE Sprzęt SET Nazwa = :name WHERE Id = :id";
    private const QUERY_UPDATE_INSTRUMENT_STATE = "UPDATE Sprzęt SET Stan = :state WHERE Id = :id";


    public function __construct($queryExecutor = null) {
        $this->queryExecutor = ($queryExecutor != null) ? $queryExecutor : new DatabaseQueryExecutor();
    }

    public function getNewInstrumentId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function getInstrumentById($id) {
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
                'message' => "Sprzęt z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getAllInstruments() {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_ALL, []);
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function addNewInstrument($inputs) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_INSERT_INSTRUMENT, [
            ':state' => $inputs['stateInstrument'],
            ':name' => $inputs['value']    
        ]);
    }

    public function deleteInstrument($id) {
        $result = $this->getInstrumentById($id);        
        if ($result['success'] && $result['record'] == null) {
            return [
                'success' => false,
                'message' => 'Sprzęt o takim ID nie istnieje'
            ];
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_INSTRUMENT, [
            ':id' => $id
        ]);
    }

    public function changeInstrumentName($id, $name) {
        $result = $this->getInstrumentById($id);        
        if ($result['success'] && $result['record'] == null) {
            return [
                'success' => false,
                'message' => 'Id jest puste lub sprzęt o takim ID nie istnieje'
            ];
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_INSTRUMENT_NAME, [
            ':name' => $name,
            ':id' => $id
        ]);
    }

    public function changeInstrumentState($id, $state, $isFromLecture = null) {
        $result = $this->getInstrumentById($id);        
        if ($result['success'] == true && $result['record'] == null) {
            return [
                'success' => false,
                'message' => 'Id jest puste lub sprzęt o takim ID nie istnieje'
            ];
        }

        if ($result['record']['Stan'] == "W użytku" && !$isFromLecture) {
            return [
                'success' => false,
                'message' => 'Nie można zmieniać zajętego sprzętu'
            ];
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_INSTRUMENT_STATE, [
            ':state' => $state,
            ':id' => $id
        ]);
    }
}