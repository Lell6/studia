<?php
namespace App\repository;

use App\service\DatabaseQueryExecutor;
use PDOException;
use Exception;

abstract class TicketRepository {
    protected $queryExecutor;
    
    protected $queryAddNewTicket;
    protected $querySelectTicketById;
    protected $querySelectAllTickets;
    protected $queryUpdateTicketStatus;
    protected $queryUpdateTicketPayment;
    protected $queryUpdateTicketPrice;

    protected $onlineTicket;
    protected $offlineTicket;

    protected $queryAddLectureToTicket;
    protected $queryDeleteLectureFromTicket;
    protected $querySelectLecturesFromTicket;
    protected $queryUpdateLectureTicketStatus;

    protected $querySelectFromCartByClientId;
    protected $querySelectOneLectureFromTicket;

    protected $querySelectTicketPrice;
    protected $querySelectAllClientTickets;

    private $lectureRepo;
    private $roomRepo;
    private $clientRepo;

    public function __construct() {
        $this->queryExecutor = new DatabaseQueryExecutor();
        $this->lectureRepo = new LectureRepository($this->queryExecutor);
        $this->roomRepo = new RoomRepository($this->queryExecutor);
        $this->clientRepo = new ClientRepository($this->queryExecutor);

        $this->initializeQueries();
    }

    abstract protected function initializeQueries();

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

    public function getNewTicketId() {
        return $this->queryExecutor->getLastInserted();
    }

    public function getTickets() {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectAllTickets, []);
        if ($result['success']) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function getOneTicket($ticketId) {
        $params = ['id' => $ticketId];
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectTicketById, $params);

        if (!$result['success']) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Bilet z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function updateTicketStatus($ticketId, $newStatus) {
        return $this->queryExecutor->executePreparedQuery($this->queryUpdateTicketStatus, [
            'id' => $ticketId,
            'newStatus' => $newStatus
        ]);
    }

    public function updateTicketPaymentMethod($ticketId, $newMethod) {
        return $this->queryExecutor->executePreparedQuery($this->queryUpdateTicketPayment, [
            'id' => $ticketId,
            'payment' => $newMethod
        ]);
    }

    public function updateTicketPrice($ticketId, $price) {
        return $this->queryExecutor->executePreparedQuery($this->queryUpdateTicketPrice, [
            'id' => $ticketId,
            'price' => $price
        ]);
    }

    public function recalculateTicketPrice($ticketId, $type) {
        $ticketPrice = 0;
        $result = $this->selectLecturesFromTicket($ticketId, $type);

        if (!$result['success']) {
            return $result;
        }
        
        $lecturesInTicket = $result['records'];
        foreach ($lecturesInTicket as $lecture) {
            $ticketPrice += $lecture['Cena'];
        }

        $result = $this->updateTicketPrice($ticketId, $ticketPrice);
        if (!$result['success']) {
            return $result;
        }
        
        return $result;
    }

    public function selectLecturesFromTicket($ticketId, $type) {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectLecturesFromTicket, [
            'ticketId' => $ticketId,
            'type' => $type
        ]);
        if ($result['success'] === true) {
            $result['records'] = $this->queryExecutor->getQueryResultMultiple();
        }

        return $result;
    }

    public function updateLectureTicketStatus($lectureTicketId, $status) {
        return $this->queryExecutor->executePreparedQuery($this->queryUpdateLectureTicketStatus, [
            'status' => $status,
            'id' => $lectureTicketId
        ]);
    }

    public function addLectureToTicket($lectureId, $ticketId, $type) {
        return $this->executeTransaction(function() use ($lectureId, $ticketId, $type) {
            $result = $this->lectureRepo->getLectureById($lectureId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            
            $lecture = $result['record'];
            if ($lecture['Cena'] == '0' || $lecture['Data'] == '1900-01-01 00:00:00' || $lecture['Sala'] == null) {
                throw new Exception('Nieprawidłowy wykład w bilecie');
            }

            $result = $this->roomRepo->getRoomByNumber($lecture['Sala']);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $room = $result['record'];
            if ($room['Pojemność'] <= $lecture['Zajętość']) {
                throw new Exception("Brak miejsc na wykład");
            }

            $result = $this->queryExecutor->executePreparedQuery($this->queryAddLectureToTicket, [
                'lectureId' => $lectureId,
                'ticketId' => $ticketId,
                'type' => $type,
                'status' => 'W koszyku'
            ]);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            return $result;
        });
    }

    public function deleteLectureFromTicket($lectureId) {
        $result = $this->getLectureInTicketById($lectureId);
        if (!$result['success']) {
            return $result;
        }

        return $this->queryExecutor->executePreparedQuery($this->queryDeleteLectureFromTicket, [
            'lectureTicketId' => $lectureId
        ]);
    }

    public function getTicketFromCartByClientId($status, $clientId) {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectFromCartByClientId, [
            'status' => $status,
            'clientId' => $clientId
        ]);
        if ($result['success'] !== true) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Bilet z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getLectureInTicketById($id) {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectOneLectureFromTicket, [
            'lectureTicketId' => $id
        ]);

        if (!$result['success']) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Bilet z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function getTicketPrice($ticketId) {
        $result = $this->queryExecutor->executePreparedQuery($this->querySelectTicketPrice, [
            'id' => $ticketId
        ]);

        if (!$result['success']) {
            return $result;
        }

        $result['record'] = $this->queryExecutor->getQueryResult();
        if ($result['record'] == null) {
            return [
                'success' => false,
                'message' => "Bilet z takim ID nie istnieje"
            ];
        }

        return $result;
    }

    public function processTicketPayment($ticketId, $payment, $type) {
        return $this->executeTransaction(function() use ($ticketId, $payment, $type) {
            $result = $this->selectLecturesFromTicket($ticketId, $type);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $lectures = $result['records'];
            foreach ($lectures as $ticketLecture) {
                $result = $this->lectureRepo->getLectureById($ticketLecture['Wykład_Id']);
                $lectureInfo = $result['record'];

                $result = $this->roomRepo->getRoomByNumber($lectureInfo['Sala']);
                $lectureRoom = $result['record'];

                if ($ticketLecture['Zajętość'] >= $lectureRoom['Pojemność']) {
                    throw new Exception("Wykład " . $ticketLecture['Tytuł'] . " jest wypełniony");
                }
            }

            foreach ($lectures as $lecture) {
                $result = $this->lectureRepo->updateLectureFill($lecture['Wykład_Id'], 1);
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }
            }
            foreach ($lectures as $lecture) {
                $result = $this->updateLectureTicketStatus($lecture['Wykład_Id'], 'Zatwierdzono');
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }
            }

            $result = $this->updateTicketPaymentMethod($ticketId, $payment);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $result = $this->updateTicketStatus($ticketId, 'Opłacony');
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            return $result;
        });
    }

    public function setTicketCancelRequest($userId, $ticketId) {
        return $this->executeTransaction(function() use ($userId, $ticketId) {
            $result = $this->clientRepo->getClientByUserId($userId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $clientId = $result['Id'];

            $result = $this->getOneTicket($ticketId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $selectedTicketClient = $result['record']['Id_Kupującego'];
            if ($selectedTicketClient != $clientId) {
                throw new Exception($result['Brak dostępu do biletu']);
            }

            $ticketStatus = $result['record']['Status'];
            if ($ticketStatus != "Opłacono") {
                throw new Exception('Bilet już został anulowany');
            }

            $result = $this->updateTicketStatus($ticketId, 'Prośba o Anulowanie');
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            return $result;
        });
    }

    public function processTicketCancel($actionType, $ticketId) {
        return $this->executeTransaction(function() use ($actionType, $ticketId) {
            $result = $this->getOneTicket($ticketId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            
            if ($actionType == 'accept') {
                $result = $this->updateTicketStatus($ticketId, 'Potwierdzono Anulowanie');
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }
        
                $result = $this->selectLecturesFromTicket($ticketId, 'online');
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }

                $lecturesInTicket = $result['records'];
                foreach ($lecturesInTicket as $lecture) {
                    $result = $this->lectureRepo->updateLectureFill($lecture['Wykład_Id'], -1);
                    if (!$result['success']) {
                        throw new Exception($result['message']);
                    }
                }
                foreach ($lecturesInTicket as $lecture) {
                    $result = $this->updateLectureTicketStatus($lecture['Id'], 'Anulowano');
                    if (!$result['success']) {
                        throw new Exception($result['message']);
                    }
                }
            } else if ($actionType == 'reject') {
                $result = $this->updateTicketStatus($ticketId, 'Skasowano Anulowanie');
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }
            }

            return $result;
        });
    }

    public function setLectureCancelRequest($userId, $lectureTicketId) {
        return $this->executeTransaction(function() use ($userId, $lectureTicketId) {
            $result = $this->clientRepo->getClientByUserId($userId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            $clientId = $result['record']['Id'];

            $result = $this->getLectureInTicketById($lectureTicketId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            $ticketId = $result['record']['Bilet_Online_Id'];
            $lectureTicketStatus = $result['record']['Status'];

            $result = $this->getOneTicket($ticketId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }
            $ticketClient = $result['record']['Id_Kupującego'];
            $ticketStatus = $result['record']['Status'];
            
            if ($ticketClient != $clientId) {
                throw new Exception('Brak dostępu do biletu');
            }
            if ($ticketStatus != "Opłacony") {
                throw new Exception('Bilet już został anulowany');
            }
            if ($lectureTicketStatus != "Zatwierdzono") {
                throw new Exception('Wykład już został anulowany');
            }
            
            $result = $this->updateLectureTicketStatus($lectureTicketId, 'Prośba o Anulowanie');
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            return $result;
        });
    }

    public function processLectureCancel($actionType, $lectureTicketId) {
        return $this->executeTransaction(function() use ($actionType, $lectureTicketId) {
            $result = $this->getLectureInTicketById($lectureTicketId);
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            if ($actionType == 'accept') {
                $result = $this->updateLectureTicketStatus($lectureTicketId, 'Potwierdzono Anulowanie');
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }

                $result = $this->getLectureInTicketById($lectureTicketId);
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }
                $lectureTicketId = $result['record']['Bilet_Online_Id'];
                $lectureId = $result['record']['Wykład_Id'];
    
                $result = $this->lectureRepo->updateLectureFill($lectureId, -1);
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }
                
                $allCanceled = true;
                $result = $this->selectLecturesFromTicket($lectureTicketId, 'online');
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }

                $ticketLectures = $result['records'];
    
                foreach ($ticketLectures as $lecture) {
                    if (!isset($lecture['Status']) || $lecture['Status'] !== 'Podtwierdzono Anulowanie' || $lecture['Status'] !== 'Prośba o Anulowanie') {
                        $allCanceled = false;
                        break;
                    }
                }
    
                if ($allCanceled) {
                    $result = $this->updateTicketStatus($lectureTicketId, 'Anulowano');
                    if (!$result['success']) {
                        throw new Exception($result['message']);
                    }
                }            
            } else if ($actionType == 'reject') {
                $result = $this->updateTicketStatus($lectureTicketId, 'Skasowano Anulowanie');
                if (!$result['success']) {
                    throw new Exception($result['message']);
                }
            }

            return $result;
        });
    }
}