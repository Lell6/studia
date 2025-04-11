<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;
use Exception;
use PDOException;

class LectureInstrumentRepository {
    private $queryExecutor;

    private const QUERY_SELECT_LECTURE_INTRUMENTS = "SELECT 
                                                            Sprzęt_W_Użyciu.Id, 
                                                            Wykład.Id AS Wykład, 
                                                            Sprzęt_W_Użyciu.Sprzęt_Id, 
                                                            Sprzęt.Nazwa
                                                        FROM 
                                                            Sprzęt_W_Użyciu
                                                        INNER JOIN 
                                                            Wykład ON Sprzęt_W_Użyciu.Wykład_Id = Wykład.Id
                                                        INNER JOIN 
                                                            Sprzęt ON Sprzęt_W_Użyciu.Sprzęt_Id = Sprzęt.Id
                                                        WHERE 
                                                            Sprzęt_W_Użyciu.Wykład_Id = :id";
    private const QUERY_SELECT_LECTURE_INSTRUMENT = "SELECT 
                                                            Sprzęt_W_Użyciu.Id, 
                                                            Wykład.Id AS Wykład, 
                                                            Sprzęt_W_Użyciu.Sprzęt_Id, 
                                                            Sprzęt.Nazwa
                                                        FROM 
                                                            Sprzęt_W_Użyciu
                                                        INNER JOIN 
                                                            Wykład ON Sprzęt_W_Użyciu.Wykład_Id = Wykład.Id
                                                        INNER JOIN 
                                                            Sprzęt ON Sprzęt_W_Użyciu.Sprzęt_Id = Sprzęt.Id
                                                        WHERE 
                                                            Sprzęt_W_Użyciu.Wykład_Id = :id
                                                        AND 
                                                            Sprzęt_W_Użyciu.Sprzęt_Id = :instrumentId";

    private const QUERY_ADD_LECTURE_INSTRUMENT = "INSERT INTO Sprzęt_W_Użyciu (Wykład_Id, Sprzęt_Id) VALUES (:lectureId, :instrumentId)";
    private const QUERY_DELETE_LECTURE_INSTRUMENT = "DELETE Sprzęt_W_Użyciu WHERE Wykład_Id = :lectureId AND Sprzęt_id = :instrumentId";
    private const QUERY_DELETE_ALL_LECTURE_INSTRUMENTS = "DELETE Sprzęt_W_Użyciu WHERE Wykład_Id = :lectureId";

    public function __construct()
    {
        $this->queryExecutor = new DatabaseQueryExecutor();
    }

    public function getLectureInstrumetns($lectureId) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_LECTURE_INTRUMENTS, [
            'id' => $lectureId
        ]);
        
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function getOneLectureInstrument($lectureId, $instrumentId) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_LECTURE_INSTRUMENT, [
            'id' => $lectureId,
            'instrumentId' => $instrumentId
        ]);

        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Rekord z takim ID nie istnieje"
            ];
        }

        return $result;
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
        } catch (PDOException $exception) {
            $this->queryExecutor->rollBackTransaction();
            return ['success' => false, 'message' => $exception->getMessage()];
        }
    }

    public function addInstrumentToLecture($lectureId, $instrumentId) {
        return $this->executeTransaction(function() use ($lectureId, $instrumentId) {
            $instrumentRepo = new InstrumentRepository($this->queryExecutor);

            $result = $instrumentRepo->getInstrumentById($instrumentId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            if ($result['record']['Stan'] != "Sprawny") {
                throw new Exception('Nie można dodać z tym stanem: ' . $result['record']['Stan']);
            }

            $result = $instrumentRepo->changeInstrumentState($instrumentId, "W użytku");
            if (!$result['success']) {                
                throw new Exception($result['message']);
            }

            $result = $this->queryExecutor->executePreparedQuery(self::QUERY_ADD_LECTURE_INSTRUMENT, [
                'lectureId' => $lectureId,
                'instrumentId' => $instrumentId
            ]);
            if (!$result['success']) {           
                throw new Exception($result['message']);
            }

            return $result;
        });
    }

    public function deleteInstrumentInLecture($lectureId, $instrumentId) {
        return $this->executeTransaction(function() use ($lectureId, $instrumentId) {
            $instrumentRepo = new InstrumentRepository($this->queryExecutor);

            $result = $instrumentRepo->getInstrumentById($instrumentId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $result = $instrumentRepo->changeInstrumentState($instrumentId, "Sprawny", true);
            if (!$result['success']) {                
                throw new Exception($result['message']);
            }

            $result = $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_LECTURE_INSTRUMENT, [
                'lectureId' => $lectureId,
                'instrumentId' => $instrumentId
            ]);
            if (!$result['success']) {                
                throw new Exception($result['message']);
            }
            
            return $result;
        });
    }

    public function deleteAllInstrumentInLecture($lectureId, $isFromLecture = null) {
        $result = $this->getLectureInstrumetns($lectureId);

        if (!$result['success']) {
            throw new Exception($result['message']);
        }
        if (empty($result['records'])) {
            return $result;
        }

        $instrumentRepo = new InstrumentRepository($this->queryExecutor);
        foreach($result['records'] as $record) {
            $id = $record['Id'];

            $result = $instrumentRepo->changeInstrumentState($id, "Sprawny", $isFromLecture);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_ALL_LECTURE_INSTRUMENTS, [
            'lectureId' => $lectureId
        ]);
    }
}