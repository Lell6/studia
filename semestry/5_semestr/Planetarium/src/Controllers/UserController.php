<?php
namespace App\controller;
use App\service\SessionHandler;
use App\service\ValidationHandler;
use App\service\FormProcessingKeyNames;
use App\service\AccessHandler;
use App\service\PagesHandler;

use App\repository\UserRepository;
use App\repository\OnlineTicketRepository;
use App\repository\ClientRepository;

class UserController {
    private $sessionHandler;
    private $validationHandler;
    private $userRepository;
    private $formProcessor;
    private $accessHandler;
    private $pagesHandler;

    public function __construct()
    {
        $this->sessionHandler = new SessionHandler();
        $this->validationHandler = new ValidationHandler();
        $this->userRepository = new UserRepository();
        $this->formProcessor = new FormProcessingKeyNames();
        $this->accessHandler = new AccessHandler();
        $this->pagesHandler = new PagesHandler();
    }

    public function logoutUser($request, $response) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $this->sessionHandler->startSession();
        $this->sessionHandler->finishSession();

        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    public function loginUser($request, $response) {        
        if (!$this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $body = $request->getParsedBody();
        $inputs['login'] = $body['login'] ?? "";
        $inputs['password'] = $body['password'] ?? "";

        if (!empty($this->validationHandler->validateInputs($inputs))) {
            return $this->pagesHandler->loadLoginPage($request, $response, null, ['error' => "Nieprawidłowy login lub hasło"]);
        }

        $result = $this->userRepository->performLogin($inputs['login'], $inputs['password']);
        if (!$result['success']) {
            return $this->pagesHandler->loadLoginPage($request, $response, null, ['error' => "Nieprawidłowy login lub hasło"]);
        }

        $this->sessionHandler->setUserDataInSession($result['record']);

        $clientRepo = new ClientRepository();
        $ticketRepo = new OnlineTicketRepository();

        $clientData = $clientRepo->getClientByUserId($this->sessionHandler->getUserValueByKey('userId'));
        $ticketData = $ticketRepo->getTicketFromCartByClientId('W koszyku', $clientData['Id']);

        if ($ticketData != null) {
            $this->sessionHandler->setTicketId($ticketData['Id']);
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }

    public function registerUser($request, $response) {        
        if ($this->accessHandler->isClientLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $body = $request->getParsedBody();
        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'register');
        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);
        $errorMessages = $this->validationHandler->validateInputs($inputValues);

        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadRegisterPage($request, $response, null, $errorMessages);
        }
        
        $inputValues['password'] = password_hash($inputValues['password'], PASSWORD_DEFAULT);        
        if ($this->accessHandler->isNoneLogged()) {
            $inputValues['privilege'] = 2;
        }
        if ($this->accessHandler->isWorkerLogged()) {
            $inputValues['privilege'] = 3;
        }

        $result = $this->userRepository->performRegister($this->accessHandler, $inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadRegisterPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    public function changeUserPassword($request, $response) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $userId = $this->sessionHandler->getUserValueByKey('userId');
        if ($userId == null) {
            return $this->pagesHandler->loadUpdatePasswordPage($request, $response, null, ["Nastąpił błąd pobierania ID"]);
        } 

        $body = $request->getParsedBody();
        $inputValues['oldPassword'] = $body['oldPassword'];
        $inputValues['newPassword'] = $body['newPassword'];
        $inputValues['newPasswordRepeat'] = $body['newPasswordRepeat'];

        $errorMessages = [];
        foreach ($inputValues as $key => $value) {
            if (!$this->validationHandler->isCorrectPassword($value)) {
                $errorMessages[] = 'Puste pola lub nieprawidłowe hasło';
            }
        }
        
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadUpdatePasswordPage($request, $response, null, $errorMessages);
        }

        $result = $this->userRepository->performPasswordUpdate($userId, $inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadUpdatePasswordPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/user')->withStatus(302);
    }

    public function changeSelectedUserData($request, $response) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;

        if ($id == null) {
            return $this->pagesHandler->loadUpdateSelectedUserPage($request, $response, null, ["Nie podano użytkownika do zmiany"]);
        } 

        $body = $request->getParsedBody();

        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'change');
        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);
        $errorMessages = $this->validationHandler->validateInputs($inputValues);

        if ($this->accessHandler->isWorkerLogged()) {
            $inputValues['privilege'] = 3;
        }
        
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadUpdateSelectedUserPage($request, $response, null, $errorMessages);
        }

        $result = $this->userRepository->performUserUpdate($this->accessHandler, $id, $inputValues);
        if (!$result['success']) {
            return $this->pagesHandler->loadUpdateSelectedUserPage($request, $response, null, [$result['message']]);
        }
        return $response->withHeader('Location', '/workers')->withStatus(302);
    }

    public function changeCurrentUserData($request, $response) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $userId = $this->sessionHandler->getUserValueByKey('userId');

        if ($userId == null) {
            return $this->pagesHandler->loadUpdateCurrentUserPage($request, $response, null, ["Nastąpił błąd pobierania ID"]);
        } 

        $body = $request->getParsedBody();

        $inputKeys = $this->formProcessor->getInputKeys($this->accessHandler, 'change');
        $inputValues = $this->formProcessor->getInputValues($inputKeys, $body);
        $errorMessages = $this->validationHandler->validateInputs($inputValues);
        
        if (!empty($errorMessages)) {
            return $this->pagesHandler->loadUpdateCurrentUserPage($request, $response, null, $errorMessages);
        }

        $this->userRepository->performUserUpdate($this->accessHandler, $userId, $inputValues);
        $this->sessionHandler->updateUserDataInSession($inputValues);

        return $response->withHeader('Location', '/user')->withStatus(302);
    }

    public function deleteSelectedUser($request, $response) {
        if ($this->accessHandler->isClientLogged() || $this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $id = (null !== $request->getAttribute('id')) ? $request->getAttribute('id') : null;
        if ($id == null) {
            return $this->pagesHandler->loadWorkerListPage($request, $response, null, ["Nie podano użytkownika do zmiany"]);
        }

        $userId = $this->sessionHandler->getUserValueByKey('userId');
        if ($id == $userId) {
            return $this->pagesHandler->loadWorkerListPage($request, $response, null, ["Nie możesz usunąć samego siebie z tego miejsca"]);
        }
        
        $userData = $this->userRepository->getUserById($id);
        if ($this->accessHandler->isWorkerLogged() && $userData['Przywileje'] == 4) {
            return $this->pagesHandler->loadWorkerListPage($request, $response, null, ["Nie masz uprawnień do usuwania"]);
        }
        
        $result = $this->userRepository->performUserDelete($this->accessHandler, $id);
        if (!$result['success']) {
            return $this->pagesHandler->loadWorkerListPage($request, $response, null, [$result['message']]);
        }

        return $response->withHeader('Location', '/workers')->withStatus(302);
    }

    public function deleteCurrentUser($request, $response) {
        if ($this->accessHandler->isNoneLogged()) {
            return $response->withHeader('Location', '/')->withStatus(302);
        }

        $userId = $this->sessionHandler->getUserValueByKey('userId');

        if ($userId == null) {
            return $this->pagesHandler->loadCurrentUserPage($request, $response, null, ["Nastąpił błąd pobierania ID"]);
        }

        $deleteResponse = $this->userRepository->performUserDelete($this->accessHandler, $userId);
        if (!$deleteResponse['success']) {
            return $this->pagesHandler->loadCurrentUserPage($request, $response, null, [$deleteResponse['message']]);
        }

        $this->sessionHandler->finishSession();
        return $response->withHeader('Location', '/')->withStatus(302);
    }
}