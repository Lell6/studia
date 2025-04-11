<?php
namespace App\controller;

use App\service\ValidationHandler;
use App\service\AccessHandler;
use App\service\DictionaryHandler;
use App\service\PagesHandler;

class DictionaryController {
    private $accessHandler;
    private $validationHandler;
    private $pagesHandler;
    private $dictionaryHandler;

    public function __construct()
    {
        $this->accessHandler = new AccessHandler();
        $this->validationHandler = new ValidationHandler();
        $this->pagesHandler = new PagesHandler();
        $this->dictionaryHandler = new DictionaryHandler();
    }

    public function addNewRecord($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $type = (null !== $request->getAttribute('type')) ? $request->getAttribute('type') : null;
        if ($type == null || !$this->validationHandler->isCorrectDictionaryName($type)) {
            return $this->pagesHandler->loadDictionaryRecordsPage($request, $response, null,  ['Słownik o tej nazwie nie istnieje']);
        }

        $body = $request->getParsedBody();
        $value = $body['value'];

        if (!$this->validationHandler->isCorrectValue($value)) {
            return $this->pagesHandler->loadDictionaryRecordsPage($request, $response, null,  ['Nieprawidłowa wartość']);
        }

        $result = $this->dictionaryHandler->selectDictionaryAndInsert($type, $value);
        if (!$result['success']) {
            return $this->pagesHandler->loadDictionaryRecordsPage($request, $response, null,  [$result['message']]);
        }

        return $response->withHeader('Location', '/dictionary/' . $type)->withStatus(302);
    }

    public function updateRecord($request, $response) {        
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $type = (null !== $request->getAttribute('type')) ? $request->getAttribute('type') : null;
        if ($type == null || !$this->validationHandler->isCorrectDictionaryName($type)) {
            return $this->pagesHandler->loadDictionaryRecordsPage($request, $response, null,  ['Słownik o tej nazwie nie istnieje']);
        }

        $body = $request->getParsedBody();
        $value = $body['value'];
        $isDefault = (isset($body['isDefault']) && $body['isDefault'] == 'on') ? 1 : 0;
        
        if (!$this->validationHandler->isCorrectValue($value)) {
            return $this->pagesHandler->loadDictionaryRecordsPage($request, $response, null,  ['Nieprawidłowa wartość']);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        
        $result = $this->dictionaryHandler->selectDictionaryAndUpdate($type, $id, $value, $isDefault);
        if (!$result['success']) {
            return $this->pagesHandler->loadDictionaryRecordsPage($request, $response, null,  [$result['message']]);
        }

        return $response->withHeader('Location', '/dictionary/' . $type)->withStatus(302);
    }

    public function deleteRecord($request, $response) {
        if ($this->accessHandler->isNoneLogged() || $this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $type = (null !== $request->getAttribute('type')) ? $request->getAttribute('type') : null;
        if ($type == null || !$this->validationHandler->isCorrectDictionaryName($type)) {
            return $this->pagesHandler->loadDictionaryRecordsPage($request, $response, null,  ['Słownik o tej nazwie nie istnieje']);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;

        $result = $this->dictionaryHandler->selectDictionaryAndRemove($type, $id);        
        if (!$result['success']) {
            return $this->pagesHandler->loadDictionaryRecordsPage($request, $response, null,  [$result['message']]);
        }

        return $response->withHeader('Location', '/dictionary/' . $type)->withStatus(302);
    }
}