<?php
namespace App\controller;

use App\repository\RoomRepository;
use App\service\ValidationHandler;
use App\service\AccessHandler;
use App\service\PagesHandler;
use App\service\FormProcessingKeyNames;
use App\service\FormGenerationKeyNames;

class RoomController {
    private $formGenerator;
    private $validationHandler;
    private $formProcessor;
    private $accessHandler;
    private $pagesHandler;

    private $roomRepository;

    public function __construct()
    {
        $this->formGenerator = new FormGenerationKeyNames();
        $this->validationHandler = new ValidationHandler();
        $this->formProcessor = new FormProcessingKeyNames();
        $this->accessHandler = new AccessHandler();
        $this->pagesHandler = new PagesHandler();
        $this->roomRepository = new RoomRepository();
    }

    public function addNewRoom($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $body = $request->getParsedBody();
        $inputValues['capacity'] = $body['capacity'];
        $inputValues['stateRoom'] = "Sprawna";
        $errorMessages = $this->validationHandler->validateInputs($inputValues);

        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, $errorMessages);
        }

        $result = $this->roomRepository->addNewRoom($inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/rooms')->withStatus(302);
    }

    public function changeRoomCapacity($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, ["Nie podano salę do zmiany"]);
        } 

        $body = $request->getParsedBody();
        $inputValues['capacity'] = $body['capacity'];
        $errorMessages = $this->validationHandler->validateInputs($inputValues);
        
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, $errorMessages);
        }

        $result = $this->roomRepository->updateRoomCapacity($id, $inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, [$result['message']]);
        }
        
        return $response->withHeader('Location', '/rooms')->withStatus(302);
    }

    public function changeRoomState($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, ["Nie podano salę do zmiany"]);
        } 

        $state = (null !== $request->getAttribute('state')) ? $request->getAttribute('state') : null;

        if ($state == "working") {
            $state = "Sprawna";
        } else if ($state == "repaired") {
            $state = "Naprawiana";
        } else if ($state == "used") {
            $state = "W użytku";
        } else {
            return $this->pagesHandler->loadInstrumentsListPage($request, $response, null, ['Nieprawidłowa wartość stanu']);
        }
        
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, $errorMessages);
        }

        $result = $this->roomRepository->updateRoomState($id, $state);
        if (!$result['success']) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, [$result['message']]);
        }
        
        return $response->withHeader('Location', '/rooms')->withStatus(302);
    }

    public function deleteSelectedRoom($request, $response) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;

        if ($id == null) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, ["Nie podano salę do zmiany"]);
        }
        
        $result = $this->roomRepository->deleteRoom($id);
        if (!$result['success']) {
            return $this->pagesHandler->loadRoomsListPage($request, $response, null, [$result['message']]);
        }
        return $response->withHeader('Location', '/rooms')->withStatus(302);
    }

}