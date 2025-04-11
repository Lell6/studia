<?php
namespace App\controller;

use App\repository\LecturerRepository;
use App\service\ValidationHandler;
use App\service\AccessHandler;
use App\service\PagesHandler;
use App\service\FormProcessingKeyNames;
use LDAP\Result;

class LecturerController {
    private $validationHandler;
    private $formProcessor;
    private $accessHandler;
    private $pagesHandler;

    private $lecturerRepository;

    public function __construct()
    {
        $this->validationHandler = new ValidationHandler();
        $this->formProcessor = new FormProcessingKeyNames();
        $this->accessHandler = new AccessHandler();
        $this->pagesHandler = new PagesHandler();
        $this->lecturerRepository = new LecturerRepository();
    }

    public function addNewLecturer($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $body = $request->getParsedBody();
        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'lecturer');
        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);
        $errorMessages = $this->validationHandler->validateInputs($inputValues);

        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadCreateNewLecturerPage($request, $response, null, $errorMessages);
        }

        if (!$this->lecturerRepository->addNewLecturer($inputValues)) {
            return $this->pagesHandler->loadCreateNewLecturerPage($request, $response, null, ["Wykładowca nie został dodany"]);
        }

        return $response->withHeader('Location', '/lecturers')->withStatus(302);
    }

    public function changeSelectedLecturerData($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadLecturersListPage($request, $response, null, ["Nie podano wykładowcę do zmiany"]);
        } 

        $body = $request->getParsedBody();

        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'lecturer');
        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);
        $errorMessages = $this->validationHandler->validateInputs($inputValues);
        
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadUpdateSelectedLecturerPage($request, $response, null, $errorMessages);
        }
        
        $result = $this->lecturerRepository->updateLecturer($id, $inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadCreateNewLecturerPage($request, $response, null, [$result['message']]);
        }
        return $response->withHeader('Location', '/lecturers')->withStatus(302);
    }

    public function deleteSelectedLecturer($request, $response) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadLecturersListPage($request, $response, null, ["Nie podano wykładowcę do zmiany"]);
        }
        
        $result = $this->lecturerRepository->deleteLecturer($id);
        if (!$result['success']) {
            return $this->pagesHandler->loadLecturersListPage($request, $response, null, [$result['message']]);
        }
        return $response->withHeader('Location', '/lecturers')->withStatus(302);
    }
}