<?php
namespace App\repository;
use App\service\DatabaseQueryExecutor;
use Exception;
use PDOException;
use DateTime;

class LectureRepository {
    private $queryExecutor;
    private $roomRepo;
    private $lectureInstrumentRepo;

    private const QUERY_SELECT_ALL = "SELECT 
                                            Wykład.Id, 
                                            Wykład.Tytuł, 
                                            Wykład.Treść_Wykładu, 
                                            Wykład.Opis, 
                                            Wykładowca.Imię + ' ' + Wykładowca.Nazwisko AS Wykładowiec,  
                                            Czas_Trwania.Długość AS 'Czas Trwania',
                                            Wykład.Sala AS 'Sala',
                                            Wykład.Data_Czas_Odbycia AS 'Data', 
                                            Wykład.Cena_Uczęstnictwa AS 'Cena',
                                            Wykład.Zajętość
                                        FROM Wykład
                                        LEFT JOIN Wykładowca 
                                        ON Wykład.Wykładowca = Wykładowca.Id
                                        LEFT JOIN Czas_Trwania
                                        ON Wykład.Czas_Trwania = Czas_Trwania.Id";
    private const QUERY_SELECT_BY_ID = "SELECT 
                                            Wykład.Id, 
                                            Wykład.Tytuł, 
                                            Wykład.Treść_Wykładu, 
                                            Wykład.Opis,
                                            Wykład.Wykładowca AS WykładowcaId,
                                            Wykład.Czas_Trwania AS Czas_TrwaniaId,
                                            Wykładowca.Imię + ' ' + Wykładowca.Nazwisko AS Wykładowiec,  
                                            Czas_trwania.Długość AS 'Czas Trwania',
                                            Wykład.Sala,
                                            Wykład.Data_Czas_odbycia AS 'Data', 
                                            Wykład.Cena_Uczęstnictwa AS 'Cena',
                                            Wykład.Zajętość
                                        FROM Wykład
                                        LEFT JOIN Wykładowca 
                                        ON Wykład.Wykładowca = Wykładowca.Id
                                        LEFT JOIN Czas_Trwania
                                        ON Wykład.Czas_Trwania = Czas_Trwania.Id
                                        WHERE Wykład.Id = :id";
    private const QUERY_INSERT_LECTURE = "INSERT 
                                          INTO Wykład 
                                            (Tytuł, Opis, Wykładowca, 
                                            Czas_Trwania, Cena_Uczęstnictwa, 
                                            Data_Czas_Odbycia, Sala)
                                          VALUES 
                                            (:title, :description, :lecturerId, :durationId, :price, :date, :roomId)";
    private const QUERY_UPDATE_LECTURE = "UPDATE Wykład
                                          SET Tytuł = :title,
                                              Opis = :description,
                                              Wykładowca = :lecturerId,
                                              Czas_Trwania = :durationId,
                                              Cena_Uczęstnictwa = :price,
                                              Data_Czas_Odbycia = :date,
                                              Sala = :roomId
                                          WHERE Wykład.Id = :id";
    private const QUERY_SET_FILEPATH = "UPDATE Wykład SET Treść_Wykładu = :contentPath WHERE Wykład.Id = :id";
    private const QUERY_DELETE_LECTURE = "DELETE Wykład WHERE Wykład.Id = :id";  
    private const QUERY_SELECT_FILEPATH = "SELECT Treść_Wykładu FROM Wykład WHERE Wykład.Id = :id";
    private const QUERY_UPDATE_LECTURE_FILL = "UPDATE Wykład SET Zajętość = Zajętość + :adjustment WHERE Wykład.Id = :id";
    private const QUERY_SELECT_LECTURES_BY_DATE = "SELECT 
                                                    Wykład.Id, 
                                                    Wykład.Czas_Trwania AS Czas_TrwaniaId,
                                                    Czas_trwania.Długość AS 'Czas Trwania',
                                                    Wykład.Sala,
                                                    Wykład.Data_Czas_odbycia AS 'Data',
                                                    Wykład.Zajętość
                                                FROM Wykład
                                                LEFT JOIN Czas_Trwania
                                                ON Wykład.Czas_Trwania = Czas_Trwania.Id
                                                WHERE Wykład.Data_Czas_odbycia = :date";

    public function __construct($queryExecutor = null)
    {
        $this->queryExecutor = ($queryExecutor != null) ? $queryExecutor : new DatabaseQueryExecutor();
        $this->roomRepo = new RoomRepository($this->queryExecutor);
        $this->lectureInstrumentRepo = new LectureInstrumentRepository($this->queryExecutor);
    }

    public function getInsertedLectureId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function getAllLectures() {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_ALL, []);
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function getLectureById($id) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_BY_ID, [
            "id" => $id
        ]);
        
        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Wykład z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getLecturesByDate($date) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_LECTURES_BY_DATE, [
            "date" => $date
        ]);

        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function getFilePath($id) {
        $result = $this->queryExecutor->executePreparedQuery(self::QUERY_SELECT_FILEPATH, [
            "id" => $id
        ]);
        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Wykład z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function updateFilePath($id, $path) {
        return $this->queryExecutor->executePreparedQuery(self::QUERY_SET_FILEPATH, [
            "id" => $id,
            "contentPath" => $path
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

    private function updateRoomStateIfNeeded($roomId, $newState)
    {
        if (!empty($roomId)) {
            return $this->roomRepo->updateRoomState($roomId, $newState, true);
        }
        return ['success' => true];
    }

    private function checkRoomCollission($date, $roomNumber, $type, $id = null) {
        $result = $this->getLecturesByDate($date);
        if (!$result['success']) {
            throw new Exception($result['message']);  
        }
        $lectures = $result['records'];

        foreach ($lectures as $lectureRecord) {
            $dateDB = new DateTime($lectureRecord['Data']);
            $dateInput = new DateTime($date);

            $sameDate = $dateDB->format('Y-m-d H:i') == $dateInput->format('Y-m-d H:i');

            if (($sameDate && $type=="create") || 
                ($sameDate && $type=="update" && $lectureRecord['Id'] != $id)) {
                $result = $this->roomRepo->getRoomByNumber($lectureRecord['Sala']);
                if (!$result['success']) {
                    throw new Exception($result['message']);                
                }
                $roomRecord = $result['record'];

                echo $roomRecord['Numer'];
                echo $roomNumber;
                if ($roomRecord['Numer'] == $roomNumber) {
                    throw new Exception("Nie można przypisać salę dwom wykładom w tym samym czasie");
                }
            }
        }
    }

    public function addNewLecture($inputs) {
        return $this->executeTransaction(function() use ($inputs) {
            if ($inputs['date'] != '1900-01-01 00:00:00') {
                $this->checkRoomCollission($inputs['date'], $inputs['roomId'], 'create');
            }

            $result = $this->updateRoomStateIfNeeded($inputs['roomId'], "W użytku");
            if ($result['success'] !== true) {
                throw new Exception($result['message']);
            }

            return $this->queryExecutor->executePreparedQuery(self::QUERY_INSERT_LECTURE, [
                "title" => $inputs['title'], 
                "description" => $inputs['description'], 
                "lecturerId" => $inputs['lecturerId'], 
                "durationId" => $inputs['durationId'],
                "price" => $inputs['price'], 
                "date" => $inputs['date'], 
                "roomId" => $inputs['roomId']
            ]);
        });
    }

    public function updateLectureById($id, $inputs) {
        return $this->executeTransaction(function() use ($id, $inputs) {
            $result = $this->getLectureById($id);
            if (!$result['success']) {
                throw new Exception($result['message']);                
            }

            $lecture = $result['record'];

            if (!empty($inputs['roomId'])) {
                $result = $this->roomRepo->getRoomByNumber($inputs['roomId']);
                if ($result['record']['Pojemność'] < $lecture['Zajętość']) {
                    throw new Exception("Wymiana sali jest niemożliwa: jest małopojemna");
                }
            }

            if ($inputs['date'] != '1900-01-01 00:00:00') {
                $this->checkRoomCollission($inputs['date'], $inputs['roomId'], 'update', $id);
            }            
            
            if (!empty($inputs['roomId'])) {
                $result = $this->updateRoomStateIfNeeded($lecture['Sala'], "Sprawna");
                if ($result['success'] !== true) {
                    throw new Exception($result['message']);
                }
            }

            $result = $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_LECTURE, [
                "title" => $inputs['title'], 
                "description" => $inputs['description'], 
                "lecturerId" => $inputs['lecturerId'], 
                "durationId" => $inputs['durationId'],
                "price" => $inputs['price'],
                "date" => $inputs['date'], 
                "roomId" => $inputs['roomId'],
                "id" => $id
            ]);

            if ($result['success'] !== true) {
                throw new Exception($result['message']);
            }

            $result = $this->updateRoomStateIfNeeded($inputs['roomId'], "W użytku");
            if ($result['success'] !== true) {
                throw new Exception($result['message']);
            }

            return $result;
        });
    }

    public function deleteLectureById($id) {
        return $this->executeTransaction(function() use ($id) {
            $result = $this->getLectureById($id);
            if (!$result['success']) {
                throw new Exception($result['message']);                
            }

            $lecture = $result['record'];
            $result = $this->updateRoomStateIfNeeded($lecture['Sala'], "Sprawna");
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $result = $this->lectureInstrumentRepo->deleteAllInstrumentInLecture($id, true);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            return $this->queryExecutor->executePreparedQuery(self::QUERY_DELETE_LECTURE, [
                "id" => $id
            ]);
        });
    }

    public function updateLectureFill($id, $adjustment) {
        $result = $this->getLectureById($id);
        if (!$result['success']) {
            return $result['message'];             
        }
        
        $result = $this->getLectureById($id);
        if (!$result['success']) {
            return $result;
        }
        
        $lectureFill = $result['record']['Zajętość'];
        if ($lectureFill == 0 && $adjustment == -1) {
            return [
                'success' => false,
                'message' => "Zajętość już jest 0"
            ];
        }

        return $this->queryExecutor->executePreparedQuery(self::QUERY_UPDATE_LECTURE_FILL, [
            "adjustment" => $adjustment,
            "id" => $id
        ]);
    }
}