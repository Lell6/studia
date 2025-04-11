<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;
use PDOException;

class RoomRepository {
    private $queryExecutor;
    private const QUERY_INSERT_ROOM = "INSERT INTO Sala (Stan, Pojemność) VALUES (:state, :capacity);";

    private const QUERY_SELECT_BY_ID = "SELECT Numer, Stan, Pojemność FROM Sala WHERE Numer = :number;";
    private const QUERY_SELECT_ALL = "SELECT Numer, Stan, Pojemność FROM Sala;";

    private const QUERY_DELETE_ROOM = "DELETE FROM Sala WHERE Numer = :number";
    private const QUERY_UPDATE_ROOM_CAPACITY = "UPDATE Sala SET Pojemność = :capacity WHERE Numer = :number;";
    private const QUERY_UPDATE_ROOM_STATE = "UPDATE Sala SET Stan = :state WHERE Numer = :number;";

    public function __construct() {
        $this->queryExecutor = new DatabaseQueryExecutor();
    }

    public function getNewRoomId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function getRoomByNumber($id) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_ID, [
            ':number' => $id
        ]);

        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Sala z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getAllRooms() {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_ALL, []);
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function addNewRoom($inputs) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_INSERT_ROOM, [
            ':state' => $inputs['stateRoom'],
            ':capacity' => $inputs['capacity']    
        ]);
    }

    public function deleteRoom($number) {
        $result = $this->getRoomByNumber($number);        
        if ($result['success'] == true && $result['record'] == null) {
            return [
                'success' => false,
                'message' => 'Sala o takim numerze nie istnieje'
            ];
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_ROOM, [
            ':number' => $number
        ]);
    }

    public function updateRoomCapacity($number, $inputs) {
        $result = $this->getRoomByNumber($number);        
        if ($result['success'] == true && $result['record'] == null) {
            return [
                'success' => false,
                'message' => 'Sala o takim numerze nie istnieje'
            ];
        }

        if ($result['record']['Stan'] == "W użytku") {
            return [
                'success' => false,
                'message' => 'Nie można zmieniać zajętej sali'
            ];
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_ROOM_CAPACITY, [
            ':capacity' => $inputs['capacity'],
            ':number' => $number
        ]);
    }

    public function updateRoomState($number, $state, $isFromLecture = null) {
        $result = $this->getRoomByNumber($number);        
        if ($result['success'] == true && $result['record'] == null) {
            return [
                'success' => false,
                'message' => 'Sala o takim numerze nie istnieje'
            ];
        }

        if ($result['record']['Stan'] == "W użytku" && !$isFromLecture) {
            return [
                'success' => false,
                'message' => 'Nie można zmieniać zajętej sali'
            ];
        }
        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_ROOM_STATE, [
            ':state' => $state,
            ':number' => $number
        ]);
    }
}