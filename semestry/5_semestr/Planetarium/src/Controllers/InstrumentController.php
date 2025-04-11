<?php
namespace App\controller;

use App\repository\InstrumentRepository;
use App\service\ValidationHandler;
use App\service\AccessHandler;
use App\service\PagesHandler;
use App\service\FormProcessingKeyNames;
use App\service\FormGenerationKeyNames;

class InstrumentController {
    private $validationHandler;
    private $formProcessor;
    private $accessHandler;
    private $pagesHandler;

    private $InstrumentRepository;

    public function __construct()
    {
        $this->validationHandler = new ValidationHandler();
        $this->formProcessor = new FormProcessingKeyNames();
        $this->accessHandler = new AccessHandler();
        $this->pagesHandler = new PagesHandler();
        $this->InstrumentRepository = new InstrumentRepository();
    }

    public function addNewInstrument($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $body = $request->getParsedBody();
        $inputValues['value'] = $body['value'];
        $inputValues['stateInstrument'] = "Sprawny";        
        $errorMessages = $this->validationHandler->validateInputs($inputValues);

        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadInstrumentsListPage($request, $response, null, $errorMessages);
        }

        $result = $this->InstrumentRepository->addNewInstrument($inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadInstrumentsListPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/instruments')->withStatus(302);
    }

    public function changeInstrumentState($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        $inputValues['state'] = (null !== $request->getAttribute('state')) ? $request->getAttribute('state') : null;

        if ($inputValues['state'] == "working") {
            $inputValues['state'] = "Sprawny";
        } else if ($inputValues['state'] == "repaired") {
            $inputValues['state'] = "Naprawiany";
        } else if ($inputValues['state'] == "used") {
            $inputValues['state'] = "Używany";
        } else {
            return $this->pagesHandler->loadInstrumentsListPage($request, $response, null, ['Nieprawidłowa wartość stanu']);
        }

        $result = $this->InstrumentRepository->changeInstrumentState($id, $inputValues['state']);
        if (!$result['success']) {
            return $this->pagesHandler->loadInstrumentsListPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/instruments')->withStatus(302);
    }

    public function changeInstrumentName($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;

        $body = $request->getParsedBody();
        $inputValues['value'] = $body['value'];
        $errorMessages = $this->validationHandler->validateInputs($inputValues);
        
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadInstrumentsListPage($request, $response, null, $errorMessages);
        }

        $result = $this->InstrumentRepository->changeInstrumentName($id, $inputValues['value']);
        if (!$result['success']) {
            return $this->pagesHandler->loadInstrumentsListPage($request, $response, null, [$result['message']]);
        }
        return $response->withHeader('Location', '/instruments')->withStatus(302);
    }

    public function deleteSelectedInstrument($request, $response) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        $result = $this->InstrumentRepository->deleteInstrument($id);
        if (!$result['success']) {
            return $this->pagesHandler->loadInstrumentsListPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/instruments')->withStatus(302);
    }
}