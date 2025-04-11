<?php
namespace App\service;

use App\repository\ClientRepository;
use App\repository\dictionary\DurationRepository;
use App\repository\dictionary\InstrumentStateRepository;
use App\repository\dictionary\PaymentMethodRepository;
use App\repository\dictionary\RoomStateRepository;
use App\repository\InstrumentRepository;
use App\repository\PositionRepository;
use App\repository\LecturerRepository;
use App\repository\RoomRepository;
use App\repository\UserRepository;
use App\repository\WorkerRepository;
use App\repository\LectureRepository;
use App\repository\LectureInstrumentRepository;
use App\repository\OnlineTicketRepository;
use App\repository\OfflineTicketRepository;
use Slim\Views\Twig;

class PagesHandler {
    private $sessionHandler;
    private $accessHandler;
    private $formGenerator;

    public function __construct()
    {
        $this->accessHandler = new AccessHandler();
        $this->sessionHandler = new SessionHandler();
        $this->formGenerator = new FormGenerationKeyNames();
    }

    private function renderPage($request, $response, $pageToLoad, $templateValues) {
        $view = Twig::fromRequest($request);
        return $view->render($response, $pageToLoad, $templateValues);
    }

    public function loadIndexPage($request, $response) {
        $loggedUserPrivilege = $this->accessHandler->getUserPrivilege();
        $login = ($loggedUserPrivilege != "") ? $this->sessionHandler->getUserValueByKey('login') : '';

        if ($this->accessHandler->isWorkerLogged() || $this->accessHandler->isAdminLogged()) {
            return $this->renderPage($request, $response, 'index/indexWorker.html.twig', ['login' => $login, 'privilege' => $loggedUserPrivilege]);
        }

        $lectureRepo = new LectureRepository();
        $templateValues['login'] = $login;
        $result = $lectureRepo->getAllLectures();
        $templateValues['lectures'] = $result['records'];   

        $roomRepo = new RoomRepository();
        foreach ($templateValues['lectures'] as &$lecture) {
            $result = $roomRepo->getRoomByNumber($lecture['Sala']);
            $lectureRoom = $result['record'];

            if ($lectureRoom['Pojemność'] <= $lecture['Zajętość']) {
                $lecture['Dostępność'] = 'false';
            } else {
                $lecture['Dostępność'] = 'true';
            }
        }

        return $this->renderPage($request, $response, 'index/index.html.twig', $templateValues);
    }

    public function loadLoginPage($request, $response, array $args = null, $errors = null) {
        if ($this->sessionHandler->getUserValueByKey('privilege') != "") {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $templateValues['error'] = $errors['error'] ?? null;
        return $this->renderPage($request, $response, 'user/loginPage.html.twig', $templateValues);
    }

    public function loadRegisterPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $templateValues['privilege'] = $this->accessHandler->getUserPrivilege();
        $templateValues['errors'] = $errors ?? null;
        $templateValues['keys'] = $this->formGenerator->getUserKeyNames('form');

        if (!$this->accessHandler->isNoneLogged()) {
            if (!$this->accessHandler->isClientLogged()) {
                $workerRepository = new WorkerRepository();
                $result = $workerRepository->getWorkHours();
                $templateValues['workhours'] = $result['records'];
    
                $result = $workerRepository->getPosition();
                $templateValues['positions'] = $result['records'];
            }
        }

        return $this->renderPage($request, $response, 'user/registerPage.html.twig', $templateValues);
    }

    public function loadCurrentUserPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $templateValues['updateLink'] = "/update/user";
        $templateValues['deleteLink'] = "/delete/user";
        $templateValues['userData'] = $this->sessionHandler->getUserData();
        
        $userHandler = new UserHandler();
        $loggedUserId = $this->sessionHandler->getUserValueByKey('userId');
        $templateValues['additionalUserData'] = $userHandler->getWholeUserDataById($loggedUserId);

        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'user/userDescription.html.twig', $templateValues);
    }

    public function loadSelectedUserPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        $userHandler = new UserHandler();
        $userRepository = new UserRepository();

        $templateValues['additionalUserData'] = $userHandler->getWholeUserDataById($id);

        if ($templateValues['additionalUserData'] == null) {
            return $this->loadWorkerListPage($request, $response, null, ['Użytkownik nie istnieje']);
        }

        $fullMonth = 10*20;
        $worhHour = $templateValues['additionalUserData']['Czas_Pracy']*20;
        $payment = $templateValues['additionalUserData']['Płaca'];

        $templateValues['additionalUserData']['Wynagrodzenie'] = round($payment/$fullMonth * $worhHour, 2);

        $result = $userRepository->getUserById($templateValues['additionalUserData']['Użytkownik']);
        $templateValues['userData'] = $result['record'];

        $templateValues['updateLink'] = "/update/user/".$id;
        $templateValues['deleteLink'] = "/delete/user/".$id;
        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'user/userDescription.html.twig', $templateValues);
    }

    public function loadUpdatePasswordPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $templateValues['errors'] = $errors;
        return $this->renderPage($request, $response, 'user/userPasswordUpdatePage.html.twig', $templateValues);
    }

    public function loadUpdateCurrentUserPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }        
        
        $templateValues['keyNames'] = $this->formGenerator->getUserKeyNames('form');
        $templateValues['updateLink'] = "/update/user";
        $templateValues['errors'] = $errors;
        
        $userHandler = new UserHandler();
        $loggedUserId = $this->sessionHandler->getUserValueByKey('userId');
        $templateValues['additionalUserData'] = $userHandler->getWholeUserDataById($loggedUserId);
        $templateValues['additionalUserData'] = array_splice($templateValues['additionalUserData'], 1);

        $templateValues['userData'] = $this->sessionHandler->getUserData();
        $templateValues['privilege'] = $this->accessHandler->getUserPrivilege();
        
        if (!$this->accessHandler->isClientLogged()) {
            $workerRepository = new WorkerRepository();
            $result = $workerRepository->getWorkHours();
            $templateValues['workhours'] = $result['records'];

            $result = $workerRepository->getPosition();
            $templateValues['positions'] = $result['records'];
        }
        
        return $this->renderPage($request, $response, 'user/userUpdatePage.html.twig', $templateValues);
    }

    public function loadUpdateSelectedUserPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }        
        
        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;

        $userHandler = new UserHandler();
        $userRepository = new UserRepository();

        $templateValues['additionalUserData'] = $userHandler->getWholeUserDataById($id);
        if ($templateValues['additionalUserData'] == null) {
            return $this->loadWorkerListPage($request, $response, null, ['Użytkownik nie istnieje']);
        }

        $result = $userRepository->getUserById($templateValues['additionalUserData']['Użytkownik']);
        $templateValues['userData'] = $result['record'];
        $templateValues['privilege'] = $this->accessHandler->getUserPrivilege();
        
        if (!$this->accessHandler->isClientLogged()) {
            $workerRepository = new WorkerRepository();
            $result = $workerRepository->getWorkHours();
            $templateValues['workhours'] = $result['records'];

            $result = $workerRepository->getPosition();
            $templateValues['positions'] = $result['records'];
        }

        $templateValues['keyNames'] = $this->formGenerator->getUserKeyNames('form');
        $templateValues['updateLink'] = "/update/user/".$id;
        $templateValues['errors'] = $errors;
        
        return $this->renderPage($request, $response, 'user/userUpdatePage.html.twig', $templateValues);
    }

    public function loadClientListPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $clientRepository = new ClientRepository();        
        $result = $clientRepository->getAllClients();        
        $templateValues['clients'] = $result['records'];

        $templateValues['errors'] = $errors;
        $templateValues['privilege'] = $this->accessHandler->getUserPrivilege();

        return $this->renderPage($request, $response, 'user/clientsPanel.html.twig', $templateValues);
    }

    public function loadWorkerListPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $workerRepository = new WorkerRepository();        
        $result = $workerRepository->getAllWorkers();        
        $templateValues['workers'] = $result['records'];

        $templateValues['errors'] = $errors;
        $templateValues['privilege'] = $this->accessHandler->getUserPrivilege();

        return $this->renderPage($request, $response, 'user/workersPanel.html.twig', $templateValues);
    }

    public function loadDictionaryRecordsPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $type = (null !== $request->getAttribute('type')) ? $request->getAttribute('type') : null;
        if ($type == 'privileges' && $this->accessHandler->isWorkerLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $validationHandler = new ValidationHandler();
        if ($type == null || !$validationHandler->isCorrectDictionaryName($type)) {
            $templateValues['errors'] = ['Słownik o tej nazwie nie istnieje'];
            return $this->renderPage($request, $response, 'dictionaryPage.html.twig', $templateValues);
        }
        
        $dictionaryHandler = new DictionaryHandler();
        $result = $dictionaryHandler->selectDictionaryAndSelectAll($type);

        if ($result['success']) {
            $keys = $dictionaryHandler->getKeysFromResult($result['records']);
        }

        $templateValues['errors'] = $errors;
        $templateValues['type'] = $type;
        $templateValues['name'] = $dictionaryHandler->getDictionaryName($type);
        $templateValues['records'] = $result['records'];        
        $templateValues['hasDefault'] = $dictionaryHandler->hasDefaultValue($type);

        if (!empty($result['records'])) {
            $templateValues['idColumnName'] = $keys[0];
            $templateValues['valueColumnName'] = $keys[1];
        }
        $templateValues['privilege'] = $this->accessHandler->getUserPrivilege();

        return $this->renderPage($request, $response, 'dictionaryPage.html.twig', $templateValues);
    }

    public function loadLecturersListPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $lecturersRepository = new LecturerRepository();
        $result = $lecturersRepository->getAllLecturers();
        
        $templateValues['lecturers'] = $result['records'];
        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'lecturerPanel.html.twig', $templateValues);
    }

    public function loadUpdateSelectedLecturerPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        $lecturerRepo = new LecturerRepository();        
        $result = $lecturerRepo->getLecturerById($id);

        if (!$result['success']) {
            return $this->loadLecturersListPage($request, $response, null, [$result['message']]);
        }

        $templateValues['formName'] = "Redagowanie Wykładowcy";
        $templateValues['actionLink'] = "/update/lecturer/".$id;
        $templateValues['actionName'] = "Zapisz zmiany";
        $templateValues['keys'] = $this->formGenerator->getLecturerKeyNames();
        $templateValues['lecturerData'] = $result['record'];
        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'lecturerCreatePage.html.twig', $templateValues);
    }

    public function loadCreateNewLecturerPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $templateValues['formName'] = "Dodawanie Wykładowcy";
        $templateValues['actionLink'] = "/lecturer";
        $templateValues['actionName'] = "Dodaj nowego wykładowcę";
        $templateValues['errors'] = $errors;
        $templateValues['keys'] = $this->formGenerator->getLecturerKeyNames();

        return $this->renderPage($request, $response, 'lecturerCreatePage.html.twig', $templateValues);
    }

    public function loadRoomsListPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $roomRepo = new RoomRepository();
        $result = $roomRepo->getAllRooms();

        $templateValues['rooms'] = $result['records'];
        $templateValues['errors'] = $errors;
        
        return $this->renderPage($request, $response, 'roomPanel.html.twig', $templateValues);
    }

    public function loadInstrumentsListPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $instrumentRepo = new InstrumentRepository();
        $result = $instrumentRepo->getAllInstruments();

        $templateValues['instruments'] = [];
        $templateValues['errors'] = $errors;

        if ($result['success'] && $result['success'] == true) {
            $templateValues['instruments'] = $result['records'];
        } else {
            $templateValues['errors'] = $result['message'];
        }
        
        return $this->renderPage($request, $response, 'instrumentPanel.html.twig', $templateValues);
    }

    public function loadPositionsListPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $positionRepo = new PositionRepository();
        $result = $positionRepo->getAllPositions();

        $templateValues['positions'] = $result['records'];
        $templateValues['errors'] = $errors;
        
        return $this->renderPage($request, $response, 'positionPanel.html.twig', $templateValues);
    }

    public function loadUpdateSelectedPositionPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;

        $positionRepo = new PositionRepository();        
        $result = $positionRepo->getPositionById($id);
        if (!$result['success'] || $result['record'] == null) {
            return $this->loadPositionsListPage($request, $response, null, ['Zawód o takim ID nie istnieje']);
        }

        $templateValues['formName'] = "Redagowanie Zawodu";
        $templateValues['actionLink'] = "/update/position/".$id;
        $templateValues['actionName'] = "Zapisz zmiany";
        $templateValues['keys'] = $this->formGenerator->getPositionKeyNames();
        $templateValues['positionData'] = $result['record'];
        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'positionCreatePage.html.twig', $templateValues);
    }

    public function loadCreateNewPositionPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $templateValues['formName'] = "Dodawanie Zawodu";
        $templateValues['actionLink'] = "/position";
        $templateValues['actionName'] = "Dodaj nowy Zawód";
        $templateValues['errors'] = $errors;
        $templateValues['keys'] = $this->formGenerator->getPositionKeyNames();

        return $this->renderPage($request, $response, 'positionCreatePage.html.twig', $templateValues);
    }

    public function loadLecturesListPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }
        
        $lectureRepo = new LectureRepository();
        $templateValues['lectures'] = $lectureRepo->getAllLectures()['records'];
        foreach ($templateValues['lectures'] as &$lecture) {
            if ($lecture['Data'] == "1900-01-01 00:00:00") {
                $lecture['Data'] = "Nie ustawiono";
            }
        }
        $templateValues['errors'] = $errors;
        
        return $this->renderPage($request, $response, 'lecture/lecturePanel.html.twig', $templateValues);
    }

    public function loadSelectedLecturePage($request, $response, $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            $id = $args['id'];
        }

        $lectureRepo = new LectureRepository();
        $lectureInstrumentRepo = new LectureInstrumentRepository();
        $instrumentRepo = new InstrumentRepository();

        $lecture = $lectureRepo->getLectureById($id)['record'];
        if ($lecture == null) {
            return $this->loadLecturesListPage($request, $response, null, ['Wykład o takim ID nie istnieje']);
        }

        $templateValues['lectureData'] = $lecture;
        if ($templateValues['lectureData']['Data'] == "1900-01-01 00:00:00") {
            $templateValues['lectureData']['Data'] = "Nie ustawiono";
        }

        $templateValues['instruments'] = $lectureInstrumentRepo->getLectureInstrumetns($id)['records'];
        $templateValues['instrumentsAll'] = $instrumentRepo->getAllInstruments()['records'];
        $templateValues['updateLink'] = "/update/lecture/".$id;
        $templateValues['instrumentLink'] = "/bindinstrument/".$id;
        $templateValues['deleteInstrumentLink'] = "/delete/bindinstrument/".$id;
        $templateValues['deleteLink'] = "/delete/lecture/".$id;
        $templateValues['contentLink'] = $templateValues['lectureData']['Treść_Wykładu'];
        $templateValues['download'] = empty($templateValues['contentLink']) ? "Nie przesłano" : "Pobierz plik";
        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'lecture/lectureDescription.html.twig', $templateValues);
    }

    public function loadUpdateSelectedLecturePage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        $lectureRepo = new LectureRepository();

        $lecture = $lectureRepo->getLectureById($id);
        if ($lecture == null) {
            return $this->loadLecturesListPage($request, $response, null, ['Wykład o takim ID nie istnieje']);
        }

        $lectureRepo = new LectureRepository();
        $lecturerRepo = new LecturerRepository();
        $durationRepo = new DurationRepository();
        $roomRepo = new RoomRepository();

        $templateValues['formName'] = "Redagowanie Wykładu";
        $templateValues['actionLink'] = "/update/lecture/".$id;
        $templateValues['actionName'] = "Zapisz zmiany";

        $templateValues['keys'] = $this->formGenerator->getLectureKeyNames();

        $result = $lectureRepo->getLectureById($id);
        $templateValues['lectureData'] = $result['record'];

        if ($templateValues['lectureData']['Zajętość'] > 0) {
            unset($templateValues['keys']['Cena']);
        }

        $date = ["", ""];
        if ($templateValues['lectureData']['Data'] != "1900-01-01 00:00:00") {
            $date = explode(' ', $templateValues['lectureData']['Data']);
        }

        $templateValues['lectureData']['Data'] = $date[0];
        $templateValues['lectureData']['Czas'] = $date[1];

        $result = $lecturerRepo->getAllLecturers();
        $templateValues['lecturers'] = $result['records'];

        $result = $durationRepo->getAllRecords();
        $templateValues['durations'] = $result['records'];

        $result = $roomRepo->getAllRooms();
        $templateValues['rooms'] = $result['records'];

        $templateValues['dateToday'] = date("Y-m-d");
        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'lecture/lectureCreatePage.html.twig', $templateValues);
    }

    public function loadCreateNewLecturePage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $lecturerRepo = new LecturerRepository();
        $durationRepo = new DurationRepository();
        $roomRepo = new RoomRepository();

        $templateValues['formName'] = "Dodawanie Wykładu";
        $templateValues['actionLink'] = "/lecture";
        $templateValues['actionName'] = "Dodaj nowy Wykład";

        $templateValues['keys'] = $this->formGenerator->getLectureKeyNames();

        $result = $lecturerRepo->getAllLecturers();
        $templateValues['lecturers'] = $result['records'];

        $result = $durationRepo->getAllRecords();
        $templateValues['durations'] = $result['records'];

        $result = $roomRepo->getAllRooms();
        $templateValues['rooms'] = $result['records'];

        $templateValues['dateToday'] = date("Y-m-d");
        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'lecture/lectureCreatePage.html.twig', $templateValues);
    }

    public function loadLectureInstrumentsPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        $lectureRepo = new LectureRepository();

        $lecture = $lectureRepo->getLectureById($id);
        if ($lecture == null) {
            return $this->loadLecturesListPage($request, $response, null, ['Wykład o takim ID nie istnieje']);
        }

        $templateValues['lectureData']['Id'] = $lecture['Id'];
        $templateValues['lectureData']['Tytuł'] = $lecture['Tytuł'];

        $instrumentRepo = new InstrumentRepository();
        $instruments = $instrumentRepo->getAllInstruments();

        foreach ($instruments as $instrument) {
            if ($instrument['Stan'] == 'Sprawny') {
                $templateValues['instruments'][] = $instrument;
            }
        };

        return $this->renderPage($request, $response, 'lecture/lectureAddInstrumentsPage.html.twig', $templateValues);
    }

    public function loadCartPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $templateValues['login'] = $this->sessionHandler->getUserValueByKey('login');
        $templateValues['errors'] = $errors;
        $ticketId = $this->sessionHandler->getCurrentTicketId();

        if (!isset($ticketId) || empty($ticketId)) {
            $templateValues['cartInfo'] = 'Brak pozycji w koszu, dodaj coś';
            return $this->renderPage($request, $response, 'cartPage.html.twig', $templateValues);
        }

        $lectureRepo = new LectureRepository();
        $templateValues['lectures'] = [];
        $lectures = [];

        if ($this->accessHandler->isClientLogged()) {
            $onlineTicketRepo = new OnlineTicketRepository();
            $result = $onlineTicketRepo->selectLecturesFromTicket($ticketId, 'online');
            $lectures = $result['records'];
        }
        else if ($this->accessHandler->isWorkerLogged() || $this->accessHandler->isAdminLogged()) {
            $offlineTicketRepo = new OfflineTicketRepository();
            $result = $offlineTicketRepo->selectLecturesFromTicket($ticketId, 'offline');
            $lectures = $result['records'];
        }        

        if (empty($lectures)) {
            $templateValues['cartInfo'] = 'Brak pozycji w koszu, dodaj coś';
            return $this->renderPage($request, $response, 'cartPage.html.twig', $templateValues);
        }

        foreach ($lectures as $ticketLecture) {
            $result = $lectureRepo->getLectureById($ticketLecture['Wykład_Id']);
            $lectureInfo = $result['record'];

            $lectureInfo['tiketPositionId'] = $ticketLecture['Id'];
            unset($lectureInfo['Treść_Wykładu']);

            $templateValues['lectures'][] = $lectureInfo;
        }

        return $this->renderPage($request, $response, 'cartPage.html.twig', $templateValues);
    }

    public function loadOrderInfoPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $templateValues['login'] = $this->sessionHandler->getUserValueByKey('login');
        $ticketId = $this->sessionHandler->getCurrentTicketId();

        if (!isset($ticketId) || empty($ticketId)) {
            $templateValues['errors'] = ['Brak pozycji w koszu, dodaj coś'];
            return $this->renderPage($request, $response, 'orderPage.html.twig', $templateValues);
        }

        $lectureRepo = new LectureRepository();
        $templateValues['lectures'] = [];
        $lectures = [];

        if ($this->accessHandler->isClientLogged()) {
            $ticketRepo = new OnlineTicketRepository();
            $type = 'online';
        } else if ($this->accessHandler->isWorkerLogged() || $this->accessHandler->isAdminLogged()) {
            $ticketRepo = new OfflineTicketRepository();
            $type = 'offline';
        }

        $result = $ticketRepo->selectLecturesFromTicket($ticketId, $type);
        $lectures = $result['records'];

        $result = $ticketRepo->getTicketPrice($ticketId);
        $price = $result['record'];
        $templateValues['ticketPrice'] = $price['Cena'];

        if (empty($lectures)) {
            $templateValues['errors'] = ['Brak pozycji w koszu, dodaj coś'];
            return $this->renderPage($request, $response, 'orderPage.html.twig', $templateValues);
        }

        foreach ($lectures as $ticketLecture) {
            $result = $lectureRepo->getLectureById($ticketLecture['Wykład_Id']);
            $lectureInfo  = $result['record'];
            unset($lectureInfo['Treść_Wykładu']);

            $templateValues['lectures'][] = $lectureInfo;
        }
        
        $clientRepo = new ClientRepository();
        $templateValues['client'] = $clientRepo->getClientByUserId($this->sessionHandler->getUserValueByKey('userId'));

        $paymentMethodsRepo = new PaymentMethodRepository();

        $result = $paymentMethodsRepo->getAllRecords();
        $templateValues['paymentMethod'] = $result['records'];
        $templateValues['errors'] = $errors;

        return $this->renderPage($request, $response, 'orderPage.html.twig', $templateValues);
    }

    public function loadTicketPanelPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        if ($args == null) {
            $type = (null !== $request->getAttribute('type')) ? $request->getAttribute('type') : null;
        } else {
            $type = isset($args['type']) ? $args['type'] : null;
        }

        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        switch ($type) {
            case 'worker':
                if ($this->accessHandler->isWorkerLogged() || $this->accessHandler->isAdminLogged()) {
                    $ticketRepo = new OfflineTicketRepository();
                    $result = $ticketRepo->getTickets();
                }
                break;

            case 'client':
                $templateValues['client'] = true;

                if ($this->accessHandler->isWorkerLogged() || $this->accessHandler->isAdminLogged()) {
                    $ticketRepo = new OnlineTicketRepository();
                    $result = $ticketRepo->getTickets();
                } elseif ($this->accessHandler->isClientLogged()) {
                    $clientRepo = new ClientRepository();
                    $result = $clientRepo->getClientByUserId($this->sessionHandler->getUserValueByKey("userId"));
                    $clientId = $result['record']['Id'];

                    $ticketRepo = new OnlineTicketRepository();
                    $result = $ticketRepo->getAllClientTickets($clientId);
                }
                break;

            default:
                return $response->withHeader('Location', '/')->withStatus(302);
        }

        if ($result && isset($result['records'])) {
            $tickets = $result['records'];
        }

        $ticketsGrouped = [];

        foreach ($tickets as $ticket) {
            $ticketId = $ticket['Id'];
            if (!isset($ticketsGrouped[$ticketId])) {
                $ticketsGrouped[$ticketId] = [
                    'Id' => $ticketId,
                    'Data_Czas_Utworzenia' => $ticket['Data_Czas_Utworzenia'],
                    'Metoda_Płatności' => $ticket['Metoda_Płatności'],
                    'Cena' => $ticket['Cena'],
                    'Status' => $ticket['Status'],
                    'Wykłady' => []
                ];

                if (isset($ticket['Id_Kupującego'])) {
                    $ticketsGrouped[$ticketId]['Id_Kupującego'] = $ticket['Id_Kupującego'];
                    $ticketsGrouped[$ticketId]['Imię_Kupującego'] = $ticket['Imię_Kupującego'];
                    $ticketsGrouped[$ticketId]['Nazwisko_Kupującego'] = $ticket['Nazwisko_Kupującego'];
                    $ticketsGrouped[$ticketId]['Email_Kupującego'] = $ticket['Email_Kupującego'];
                }
            }
            
            $ticketsGrouped[$ticketId]['Wykłady'][] = [
                'Wykład_Id' => $ticket['Wykład_Id'],
                'Tytuł' => $ticket['Tytuł'],
                'Cena' => $ticket['Cena_Wykładu'],
                'Status' => $ticket['Status_Wykładu']
            ];
        }

        $templateValues['tickets'] = array_values($ticketsGrouped);
        $templateValues['errors'] = $errors;

        if ($this->accessHandler->isClientLogged()) {
            return $this->renderPage($request, $response, 'ticketPanelClient.html.twig', $templateValues);
        }
        if ($this->accessHandler->isWorkerLogged() || $this->accessHandler->isAdminLogged()) {            
            return $this->renderPage($request, $response, 'ticketPanel.html.twig', $templateValues);
        }
    }

    public function loadLectureTicketPage($request, $response, array $args = null, $errors = null) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $lectureRepo = new LectureRepository();
        $result = $lectureRepo->getAllLectures();

        $templateValues['lectures'] = $result['records'];

        $roomRepo = new RoomRepository();
        foreach ($templateValues['lectures'] as &$lecture) {
            $result = $roomRepo->getRoomByNumber($lecture['Sala']);
            $lectureRoom = $result['record'];

            if ($lectureRoom['Pojemność'] <= $lecture['Zajętość']) {
                $lecture['Dostępność'] = false;
            } else {
                $lecture['Dostępność'] = true;
            }
        }

        return $this->renderPage($request, $response, 'lectureTicketList.html.twig', $templateValues);
    }
}