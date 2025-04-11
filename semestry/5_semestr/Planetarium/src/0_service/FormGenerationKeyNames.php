<?php
namespace App\service;

class FormGenerationKeyNames {
    private $accessHandler;
    public $userKeyNames = [
        'Login' => 'login',
        'Imię' => 'name',
        'Nazwisko' => 'surname',
        'Email' => 'email',
        'Numer_Komórkowy' => 'phone',
        'Miasto' => 'city',
        'Ulica' => 'street',
        'Numer_Domu' => 'house',
        'Numer_Mieszkania' => 'apartment',
        'Zawód' => 'position',
        'Czas_Pracy' => 'workhour',
        'Przywileje' => 'privilege'
    ];
    public $lecturerKeyNames = [
        'Imię' => 'name',
        'Nazwisko' => 'surname',
        'Email' => 'email',
        'Numer_Komórkowy' => 'phone',
        'Liczba_Przeprowadzonych_Wykładów' => 'lecturesMade',
        'Płaca_Za_Wykład' => 'payment'
    ];
    public $lectureKeyNames = [
        'Tytuł' => 'title',
        'Opis' => 'description',
        'Wykładowiec' => 'lecturerId',
        'Czas_Trwania' => 'durationId',
        'Treść_Wykładu' => 'content',
        'Cena' => 'price',
        'Data_Czas_Odbycia' => 'date',
        'Sala' => 'roomId'
    ];
    public $roomKeyNames = [
        'Stan' => 'state',
        'Pojemność' => 'capacity'
    ];
    public $instrumentKeyNames = [
        'Stan' => 'stateInstrument',
        'Nazwa' => 'name'
    ];
    public $positionKeyNames = [
        'Zawód' => 'position',
        'Płaca_Etat' => 'payment'
    ];

    public function __construct() {
        $this->accessHandler = new AccessHandler();
    }

    private function getClientKeyNames() {
        return array_splice($this->userKeyNames, 0, 5, true);
    }

    private function getClientRegisterKeyNames() {
        return array_splice($this->userKeyNames, 0, 4, true);
    }

    private function getWorkerKeyNames() {
        return array_splice($this->userKeyNames, 0, -1);
    }

    private function getWorkerKeyNamesWithPrivilege() {
        return $this->userKeyNames;
    }

    public function getUserKeyNames($context) {
        $keyNames = [];
    
        if ($this->accessHandler->isNoneLogged()) {
            $keyNames = $this->getClientRegisterKeyNames();
        } else if ($this->accessHandler->isClientLogged()) {
            $keyNames = $this->getClientKeyNames();
        } else if ($this->accessHandler->isWorkerLogged()) {
            $keyNames = ($context === 'form') ? $this->getWorkerKeyNames() : $this->getWorkerKeyNamesWithPrivilege();
        } else if ($this->accessHandler->isAdminLogged()) {
            $keyNames = $this->getWorkerKeyNamesWithPrivilege();
        }
    
        if ($context === 'form') {
            return array_splice($keyNames, 1);
        }
    
        return $keyNames;
    }

    public function getLecturerKeyNames() {
        return $this->lecturerKeyNames;
    }

    public function getLectureKeyNames() {
        return $this->lectureKeyNames;
    }

    public function getRoomKeyNames() {
        return $this->roomKeyNames;
    }

    public function getInstrumentKeyNames() {
        return $this->instrumentKeyNames;
    }

    public function getPositionKeyNames() {
        return $this->positionKeyNames;
    }
}