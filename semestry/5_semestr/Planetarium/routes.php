<?php

use App\controller\DictionaryController;
use App\service\PagesHandler;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\controller\UserController;
use App\controller\LecturerController;
use App\controller\LectureController;
use App\controller\LectureInstrumentController;
use App\controller\RoomController;
use App\controller\InstrumentController;
use App\controller\PositionController;
use App\controller\TicketController;

$twig = Twig::create('src/templates/', ['cache' => false]);
$app = AppFactory::create();
$app->add(TwigMiddleware::create($app, $twig));

return function (App $app) {
    $app->get('/css/{file}', function (Request $request, Response $response, $args) {
        $filePath = __DIR__ . '/src/templates/css/' . $args['file'];
        if (file_exists($filePath)) {
            $response->getBody()->write(file_get_contents($filePath));
            return $response->withHeader('Content-Type', 'text/css');
        }
        return $response->withStatus(404, 'CSS file not found');
    });
    $app->get('/js/{file}', function (Request $request, Response $response, $args) {
        $filePath = __DIR__ . '/src/templates/js/' . $args['file'];
        if (file_exists($filePath)) {
            $response->getBody()->write(file_get_contents($filePath));
            return $response->withHeader('Content-Type', 'application/javascript');
        }
        return $response->withStatus(404, 'JS file not found');
    });

    $app->get('/', [PagesHandler::class, 'loadIndexPage']);

    $app->get('/login', [PagesHandler::class, 'loadLoginPage']);
    $app->post('/login', [UserController::class, 'loginUser']);    

    $app->get('/logout', [UserController::class, 'logoutUser']);

    $app->get('/register', [PagesHandler::class, 'loadRegisterPage']);
    $app->post('/register', [UserController::class, 'registerUser']);

    $app->get('/user', [PagesHandler::class, 'loadCurrentUserPage']);
    $app->get('/user/{id}', [PagesHandler::class, 'loadSelectedUserPage']);

    $app->get('/update/user', [PagesHandler::class, 'loadUpdateCurrentUserPage']);
    $app->get('/update/user/{id}', [PagesHandler::class, 'loadUpdateSelectedUserPage']);
    $app->get('/update/password/user', [PagesHandler::class, 'loadUpdatePasswordPage']);

    $app->post('/update/user', [UserController::class, 'changeCurrentUserData']);
    $app->post('/update/user/{id}', [UserController::class, 'changeSelectedUserData']);
    $app->post('/update/password/user', [UserController::class, 'changeUserPassword']);

    $app->get('/workers', [PagesHandler::class, 'loadWorkerListPage']);
    $app->get('/clients', [PagesHandler::class, 'loadClientListPage']);

    $app->get('/delete/user', [UserController::class, 'deleteCurrentUser']);
    $app->post('/delete/user', [UserController::class, 'deleteCurrentUser']);

    $app->get('/delete/user/{id}', [UserController::class, 'deleteSelectedUser']);
    $app->post('/delete/user/{id}', [UserController::class, 'deleteSelectedUser']);

    $app->get('/lecturers', [PagesHandler::class, 'loadLecturersListPage']);
    $app->get('/lecturer', [PagesHandler::class, 'loadCreateNewLecturerPage']);
    $app->post('/lecturer', [LecturerController::class, 'addNewLecturer']);

    $app->get('/update/lecturer/{id}', [PagesHandler::class, 'loadUpdateSelectedLecturerPage']);
    $app->post('/update/lecturer/{id}', [LecturerController::class, 'changeSelectedLecturerData']);

    $app->get('/delete/lecturer/{id}', [LecturerController::class, 'deleteSelectedLecturer']);
    $app->post('/delete/lecturer/{id}', [LecturerController::class, 'deleteSelectedLecturer']);

    $app->get('/dictionary/{type}', [PagesHandler::class, 'loadDictionaryRecordsPage']);
    $app->post('/dictionary/{type}', [DictionaryController::class, 'addNewRecord']);
    $app->post('/dictionary/update/{type}/{id}', [DictionaryController::class, 'updateRecord']);
    $app->get('/dictionary/delete/{type}/{id}', [DictionaryController::class, 'deleteRecord']);

    $app->get('/rooms', [PagesHandler::class, 'loadRoomsListPage']);
    $app->post('/room', [RoomController::class, 'addNewRoom']);

    $app->post('/update/room/{id}', [RoomController::class, 'changeRoomCapacity']);
    $app->get('/update/roomState/{id}/{state}', [RoomController::class, 'changeRoomState']);

    $app->get('/delete/room/{id}', [RoomController::class, 'deleteSelectedRoom']);
    $app->post('/delete/room/{id}', [RoomController::class, 'deleteSelectedRoom']);

    $app->get('/instruments', [PagesHandler::class, 'loadInstrumentsListPage']);
    $app->post('/instrument', [InstrumentController::class, 'addNewInstrument']);

    $app->post('/update/instrument/{id}', [InstrumentController::class, 'changeInstrumentName']);
    $app->get('/update/instrumentState/{id}/{state}', [InstrumentController::class, 'changeInstrumentState']);

    $app->get('/delete/instrument/{id}', [InstrumentController::class, 'deleteSelectedInstrument']);
    $app->post('/delete/instrument/{id}', [InstrumentController::class, 'deleteSelectedInstrument']);

    $app->get('/positions', [PagesHandler::class, 'loadPositionsListPage']);
    $app->get('/position', [PagesHandler::class, 'loadCreateNewPositionPage']);
    $app->post('/position', [PositionController::class, 'addNewPosition']);

    $app->get('/update/position/{id}', [PagesHandler::class, 'loadUpdateSelectedPositionPage']);
    $app->post('/update/position/{id}', [PositionController::class, 'changeSelectedPositionData']);

    $app->get('/delete/position/{id}', [PositionController::class, 'deleteSelectedPosition']);
    $app->post('/delete/position/{id}', [PositionController::class, 'deleteSelectedPosition']);

    $app->get('/lectures', [PagesHandler::class, 'loadLecturesListPage']);
    $app->get('/lecture/{id}', [PagesHandler::class, 'loadSelectedLecturePage']);
    $app->get('/lecture', [PagesHandler::class, 'loadCreateNewLecturePage']);
    $app->post('/lecture', [LectureController::class, 'addNewLecture']);

    $app->get('/update/lecture/{id}', [PagesHandler::class, 'loadUpdateSelectedLecturePage']);
    $app->post('/update/lecture/{id}', [LectureController::class, 'changeSelectedLectureData']);

    $app->get('/delete/lecture/{id}', [LectureController::class, 'deleteSelectedLecture']);
    $app->post('/delete/lecture/{id}', [LectureController::class, 'deleteSelectedLecture']);

    $app->post('/bindinstrument/{lectureId}', [LectureInstrumentController::class, 'addLectureInstruments']);
    $app->get('/delete/bindinstrument/{lectureId}/{instrumentId}', [LectureInstrumentController::class, 'deleteLectureInstrument']);
    $app->post('/delete/bindinstrument/{lectureId}/{instrumentId}', [LectureInstrumentController::class, 'deleteLectureInstrument']);

    $app->get('/download/lecture/content/{id}', [LectureController::class, 'downloadSelectedLectureContent']);

    $app->get('/cart', [PagesHandler::class, 'loadCartPage']);
    $app->post('/add/cart/{lectureId}', [TicketController::class, 'addLectureToTicket']);
    $app->post('/remove/cart/{lectureTicketId}', [TicketController::class, 'removeLectureFromTicket']);

    $app->get('/buy', [PagesHandler::class, 'loadOrderInfoPage']);
    $app->post('/buy', [TicketController::class, 'checkLecturesAndPayForTicket']);

    $app->get('/tickets/{type}', [PagesHandler::class, 'loadTicketPanelPage']);
    $app->get('/ticket', [PagesHandler::class, 'loadLectureTicketPage']);
    $app->post('/ticket/{lectureId}', [TicketController::class, 'addLectureToTicket']);
    $app->get('/remove/ticket/{lectureTicketId}', [TicketController::class, 'removeLectureFromTicket']);
    $app->post('/remove/ticket/{lectureTicketId}', [TicketController::class, 'removeLectureFromTicket']);

    $app->get('/cancel/lecture/{lectureTicketId}', [TicketController::class, 'sendLectureCancelRequest']);
    $app->post('/cancel/lecture/{lectureTicketId}/{status}', [TicketController::class, 'processLectureCancelRequest']);

    $app->get('/cancel/ticket/{ticketId}', [TicketController::class, 'sendTicketCancelRequest']);
    $app->post('/cancel/ticket/{ticketId}/{status}', [TicketController::class, 'processTicketCancelRequest']);

    // Define a catch-all route to handle undefined routes
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'errorPage.html.twig', ['error' => 'Page not found']);
    });
};