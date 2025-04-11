<?php
namespace App\controller;

use App\repository\ClientRepository;
use App\repository\LectureRepository;
use App\repository\RoomRepository;
use App\repository\OnlineTicketRepository;
use App\repository\OfflineTicketRepository;
use App\service\ValidationHandler;
use App\service\AccessHandler;
use App\service\PagesHandler;
use App\service\FormProcessingKeyNames;
use App\service\SessionHandler;

class TicketController {
    private $validationHandler;
    private $formProcessor;
    private $accessHandler;
    private $sessionHandler;
    private $pagesHandler;
    private $onlineTicketRepository;
    private $offlineTicketRepository;

    private $ticketRepo;
    private $isOnline;
    private $type;

    public function __construct() {
        $this->validationHandler = new ValidationHandler();
        $this->formProcessor = new FormProcessingKeyNames();
        $this->accessHandler = new AccessHandler();
        $this->pagesHandler = new PagesHandler();
        $this->onlineTicketRepository = new OnlineTicketRepository();
        $this->offlineTicketRepository = new OfflineTicketRepository();
        $this->sessionHandler = new SessionHandler();
        
        $this->isOnline = $this->checkIsOnline();
        $this->type = $this->isOnline? 'online' : 'offline';
        $this->ticketRepo = $this->isOnline ? $this->onlineTicketRepository : $this->offlineTicketRepository;
    }

    private function checkIsOnline() {
        $isOnline = $this->accessHandler->isClientLogged();
        $isOffline = $this->accessHandler->isWorkerLogged() || $this->accessHandler->isAdminLogged();

        if ($isOnline) {
            return true;
        }
        if ($isOffline) {
            return false;
        }
    }

    private function redirectPage($response) {
        if ($this->isOnline) {
            return $response->withHeader('Location', '/cart')->withStatus(302);
        }
        else {
            return $response->withHeader('Location', '/tickets/worker')->withStatus(302);
        }
    }

    private function renderPage($request, $response, $message) {
        if ($this->isOnline) {
            return $this->pagesHandler->loadCartPage($request, $response, null, [$message]);
        }
        if (!$this->isOnline) {
            return $this->pagesHandler->loadLectureTicketPage($request, $response, ['type' => 'worker'], [$message]);
        }
    }

    public function incrementLectureFill($lectures) {
        $lectureRepo = new LectureRepository();
        foreach ($lectures as $ticketLecture) {
            $lectureRepo->updateLectureFill($ticketLecture['Wykład_Id'], 1);
        }
    }

    public function decrementLectureFill($lectures) {
        $lectureRepo = new LectureRepository();
        foreach ($lectures as $ticketLecture) {
            $lectureRepo->updateLectureFill($ticketLecture['Wykład_Id'], -1);
        }
    }

    public function addNewTicketToSession($request, $response, $type = null) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $result = $this->ticketRepo->addNewTicket(date('Y-m-d H:i:s'), 'W koszyku', 1, 0, $this->sessionHandler);
        if (!$result['success']) {
            return $this->renderPage($request, $response, $result['message']);
        }

        $ticketId = $this->ticketRepo->getNewTicketId();

        $this->sessionHandler->setTicketId($ticketId);
        if ($type !== 'lecture') {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    }

    public function addLectureToTicket($request, $response) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $lectureId = $request->getAttribute('lectureId') ?? null;

        if (!$this->sessionHandler->getCurrentTicketId()) {
            $this->addNewTicketToSession($request, $response, 'lecture');
        }

        $ticketId = $this->sessionHandler->getCurrentTicketId();

        $result = $this->ticketRepo->addLectureToTicket($lectureId, $ticketId, $this->type);
        if (!$result['success']) {
            return $this->renderPage($request, $response, $result['message']);
        }

        $result = $this->ticketRepo->recalculateTicketPrice($ticketId, $this->type);
        if (!$result['success']) {
            return $this->renderPage($request, $response, $result['message']);
        }

        return $this->redirectPage($response);
    }

    public function removeLectureFromTicket($request, $response) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $lectureTicketId = $request->getAttribute('lectureTicketId') ?? null;
        if (!$this->sessionHandler->getCurrentTicketId()) {
            $this->addNewTicketToSession($request, $response);
        }

        $ticketId = $this->sessionHandler->getCurrentTicketId();

        $result = $this->ticketRepo->deleteLectureFromTicket($lectureTicketId);
        if (!$result['success']) {
            $this->renderPage($request, $response, $result['message']);
        }

        $result = $this->ticketRepo->recalculateTicketPrice($ticketId, $this->isOnline);
        if (!$result['success']) {
            $this->renderPage($request, $response, $result['message']);
        }

        return $this->redirectPage($response);
    }

    public function checkLecturesAndPayForTicket($request, $response) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        if (!$this->sessionHandler->getCurrentTicketId()) {
            $this->addNewTicketToSession($request, $response);
        }

        $ticketId = $this->sessionHandler->getCurrentTicketId();
        $payment = $request->getParsedBody()['payment'] ?? null;

        if (!$this->validationHandler->isCorrectPaymentMethod($payment)) {
            return $this->pagesHandler->loadOrderInfoPage($request, $response, null, ['Nieprawidłowa metoda płatności']);
        }

        $result = $this->ticketRepo->processTicketPayment($ticketId, $payment, $this->type);
        if (!$result['success']) {
            return $this->pagesHandler->loadOrderInfoPage($request, $response, null, [$result['message']]);
            $this->renderPage($request, $response, $result['message']);
        }

        $this->sessionHandler->removeTicket();
        return $this->redirectPage($response);
    }

    public function sendTicketCancelRequest($request, $response) {
        if (!$this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $ticketId = (null !== $request->getAttribute('ticketId')) ? $request->getAttribute('ticketId') : 0;
        if ($ticketId == 0) {
            return $this->pagesHandler->loadTicketPanelPage($request, $response, ['type' => 'client'], ['Nieprawidłowy bilet']);
        }
        $userId = $this->sessionHandler->getUserValueByKey('userId');
        
        $result = $this->ticketRepo->setTicketCancelRequest($userId, $ticketId);
        if (!$result['success']) {
            return $this->pagesHandler->loadTicketPanelPage($request, $response, ['type' => 'client'], [$result['message']]);
        }

        return $response->withHeader('Location', '/tickets/client')->withStatus(302);
    }

    public function processTicketCancelRequest($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $actionType = (null !== $request->getAttribute('status')) ? $request->getAttribute('status') : null;
        if ($actionType == null || ($actionType != 'accept' && $actionType != 'reject')) {
            return $this->pagesHandler->loadTicketPanelPage($request, $response, ['type' => 'client'], ['Nieprawidłowa akcja']);
        }

        $ticketId = (null !== $request->getAttribute('ticketId')) ? $request->getAttribute('ticketId') : 0;
        $result = $this->ticketRepo->processTicketCancel($actionType, $ticketId);
        if (!$result['success']) {
            return $this->pagesHandler->loadTicketPanelPage($request, $response, ['type' => 'client'], [$result['message']]);
        }

        return $response->withHeader('Location', '/tickets/client')->withStatus(302);
    }

    public function sendLectureCancelRequest($request, $response) {
        if (!$this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $lectureTicketId = (null !== $request->getAttribute('lectureTicketId')) ? $request->getAttribute('lectureTicketId') : 0;
        $userId = $this->sessionHandler->getUserValueByKey('userId');

        $this->ticketRepo = new OnlineTicketRepository();
        $result = $this->ticketRepo->setLectureCancelRequest($userId, $lectureTicketId);
        if (!$result['success']) {
            return $this->pagesHandler->loadTicketPanelPage($request, $response, ['type' => 'client'], [$result['message']]);
        }
        
        return $response->withHeader('Location', '/tickets/client')->withStatus(302);
    }

    public function processLectureCancelRequest($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $lectureTicketId = (null !== $request->getAttribute('lectureTicketId')) ? $request->getAttribute('lectureTicketId') : 0;
        $actionType = (null !== $request->getAttribute('status')) ? $request->getAttribute('status') : null;
        if ($actionType == null || ($actionType != 'accept' && $actionType != 'reject')) {
            return $this->pagesHandler->loadTicketPanelPage($request, $response, ['type' => 'client'], ['Nieprawidłowa akcja']);
        }
        
        $this->ticketRepo = new OnlineTicketRepository();
        $result = $this->ticketRepo->processLectureCancel($actionType, $lectureTicketId);
        if (!$result['success']) {
            return $this->pagesHandler->loadTicketPanelPage($request, $response, ['type' => 'client'], [$result['message']]);
        }

        return $response->withHeader('Location', '/tickets/client')->withStatus(302);
    }
}