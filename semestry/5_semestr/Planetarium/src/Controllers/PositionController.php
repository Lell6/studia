<?php
namespace App\controller;

use App\repository\PositionRepository;
use App\service\ValidationHandler;
use App\service\AccessHandler;
use App\service\PagesHandler;
use App\service\FormProcessingKeyNames;
use App\service\FormGenerationKeyNames;

class PositionController {
    private $formGenerator;
    private $validationHandler;
    private $formProcessor;
    private $accessHandler;
    private $pagesHandler;

    private $positionRepository;

    public function __construct()
    {
        $this->formGenerator = new FormGenerationKeyNames();
        $this->validationHandler = new ValidationHandler();
        $this->formProcessor = new FormProcessingKeyNames();
        $this->accessHandler = new AccessHandler();
        $this->pagesHandler = new PagesHandler();
        $this->positionRepository = new PositionRepository();
    }

    public function addNewPosition($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $body = $request->getParsedBody();
        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'position');
        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);
        $errorMessages = $this->validationHandler->validateInputs($inputValues);

        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadCreateNewPositionPage($request, $response, null, $errorMessages);
        }

        $result = $this->positionRepository->addNewPosition($inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadCreateNewPositionPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/positions')->withStatus(302);
    }

    public function changeSelectedPositionData($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadPositionsListPage($request, $response, null, ["Nie podano zawodu do zmiany"]);
        } 

        $body = $request->getParsedBody();

        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'position');
        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);
        $errorMessages = $this->validationHandler->validateInputs($inputValues);
        
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadUpdateSelectedPositionPage($request, $response, null, $errorMessages);
        }

        $result = $this->positionRepository->updatePosition($id, $inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadCreateNewPositionPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/positions')->withStatus(302);
    }

    public function deleteSelectedPosition($request, $response) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadPositionsListPage($request, $response, null, ["Nie podano zawodu do zmiany"]);
        }
        
        $result = $this->positionRepository->deletePosition($id);
        if (!$result['success']) {
            return $this->pagesHandler->loadPositionsListPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/positions')->withStatus(302);
    }
}