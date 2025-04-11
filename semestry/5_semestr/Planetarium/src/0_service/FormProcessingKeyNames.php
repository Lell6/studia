<?php
namespace App\service;

class FormProcessingKeyNames {    
    public function getInputValues($inputNames, $body) {
        $inputs = [];
        foreach ($inputNames as $inputName) {
            if ($inputName == 'content') {
                $inputs[$inputName] = (!empty($_FILES['content']['tmpName'])) ? $_FILES['content'] : "";
            } else {
                $inputs[$inputName] = $body[$inputName] ?? "";
            }
        }

        return $inputs;
    }

    public function separateForChange($inputKeys) {
        unset($inputKeys[0]);
        unset($inputKeys[1]);
        unset($inputKeys[2]);

        return $inputKeys;
    }

    public function getClientInputKeys() {
        return ['login', 'password', 'repeatPassword', 'name', 'surname', 'email', 'phone'];
    }

    public function getWorkerInputKeys() {
        return [
            'login', 'password', 'repeatPassword', 
            'name', 'surname', 'email', 'phone', 
            'city', 'street', 'house', 'apartment', 
            'position', 'workhour'
        ];
    }

    public function getWorkerInputKeysWithPrivilege() {
        return [
            'login', 'password', 'repeatPassword', 
            'name', 'surname', 'email', 'phone', 
            'city', 'street', 'house', 'apartment', 
            'position', 'workhour', 'privilege'
        ];
    }

    public function getLecturerInputKeys() {
        return [
            'name', 'surname', 'email', 'phone', 
            'lecturesMade', 'payment'
        ];
    }

    public function getLectureInputKeys() {
        return [
            'title', "description", "lecturerId", "durationId", "content", "price", "date", "time", "roomId"
        ];
    }

    public function getRoomInputKeys() {
        return [
            'state', 'capacity'
        ];
    }

    public function getInstrumentInputKeys() {
        return [
            'stateInstrument', 'name'
        ];
    }

    public function getPositionInputKeys() {
        return [
            'position', 'payment'
        ];
    }

    public function getInputKeys($accessHandler, $type) {
        $inputs = [];

        if ($type == 'lecturer') {
            $inputs = $this->getLecturerInputKeys();
        }
        else if ($type == 'lecture') {
            $inputs = $this->getLectureInputKeys();
        }
        else if ($type == 'room') {
            $inputs = $this->getRoomInputKeys();
        }
        else if ($type == 'instrument') {
            $inputs = $this->getInstrumentInputKeys();
        }
        else if ($type == 'position') {
            $inputs = $this->getPositionInputKeys();
        }
        else if ($type == 'register' && $accessHandler->isNoneLogged()) {
            $inputs = $this->getClientInputKeys();
        }
        else if ($type == 'change' && $accessHandler->isClientLogged()) {
            $inputs = $this->getClientInputKeys();
        }
        else if ($accessHandler->isWorkerLogged()) {
            $inputs = $this->getWorkerInputKeys();
        }
        else if ($accessHandler->isAdminLogged()) {
            $inputs = $this->getWorkerInputKeysWithPrivilege();
        }

        if ($type == 'change') {
            $inputs = $this->separateForChange($inputs);
        }

        return $inputs;
    }
}