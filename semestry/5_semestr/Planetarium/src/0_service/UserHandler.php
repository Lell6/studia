<?php
namespace App\service;
use App\repository\ClientRepository;
use App\repository\WorkerRepository;

class UserHandler {
    private $accessHandler;

    public function __construct() {
        $this->accessHandler = new AccessHandler();
    }

    public function getWholeUserDataById($userId) {
        $templateValues['userData'] = [];

        if ($this->accessHandler->isClientLogged()) {
            $clientRepo = new ClientRepository();
            $result = $clientRepo->getClientByUserId($userId);
            $templateValues['userData'] = $result['record'];

            return $templateValues['userData'];
        }

        $workerRepo = new WorkerRepository();
        $result = $workerRepo->getWorkerByUserId($userId);
        $templateValues['userData'] = isset($result['record']) ? $result['record'] : null;

        if ($this->accessHandler->isWorkerLogged()) {
            if ($templateValues['userData'] == null || $templateValues['userData']['Przywileje'] == 4) {
                return null;
            }

            $templateValues['userData'] = array_splice($templateValues['userData'], 0, -1);
        }

        return $templateValues['userData'];
    }
}