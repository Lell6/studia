<?php
namespace App\service;

use App\repository\dictionary\DurationRepository;
use App\repository\dictionary\InstrumentStateRepository;
use App\repository\dictionary\PaymentMethodRepository;
use App\repository\dictionary\PrivilegesRepository;
use App\repository\dictionary\RoomStateRepository;
use App\repository\dictionary\TicketTypeRepository;
use App\repository\dictionary\WorkhourRepository;

class DictionaryHandler {
    private $durationRepo;
    private $paymentRepo;
    private $privilegesRepo;
    private $roomStateRepo;
    private $workhourRepo;
    private $instrumentStateRepo;

    private $repositories = [];
    private $names = [
        "duration" => "Czas trwania",
        "payment" => "Metoda Płatności",
        "privileges" => "Przywileje",
        "workhour" => "Czas Pracy"
    ];

    public function __construct()
    {
        $this->durationRepo = new DurationRepository();
        $this->paymentRepo = new PaymentMethodRepository();
        $this->privilegesRepo = new PrivilegesRepository();
        $this->workhourRepo = new WorkhourRepository();

        $this->repositories = [
            "duration" => $this->durationRepo,
            "payment" => $this->paymentRepo,
            "privileges" => $this->privilegesRepo,
            "workhour" => $this->workhourRepo
        ];
    }

    public function selectDictionaryAndInsert($type, $value, $isDefault = 0) {
        if ($type == 'payment' || $type == 'workhour') {
            return $this->repositories[$type]->insertNewRecord($value);
        } else if ($type == 'duration' || $type == 'privileges') {
            return $this->repositories[$type]->insertNewRecordWithDefault($value, $isDefault);
        }

        return [
            'success' => false,
            'message' => 'Słownik nie istnieje'
        ];
    }

    public function selectDictionaryAndUpdate($type, $id, $value, $isDefault) {
        if ($type == 'payment' || $type == 'workhour') {
            return $this->repositories[$type]->updateRecord($id, $value);
        } else if ($type == 'duration' || $type == 'privileges') {
            return $this->repositories[$type]->updateRecordWithDefault($id, $value, $isDefault);
        }

        return [
            'success' => false,
            'message' => 'Słownik nie istnieje'
        ];
    }

    public function selectDictionaryAndRemove($type, $id) {
        if (array_key_exists($type, $this->repositories)) {
            return $this->repositories[$type]->deleteRecord($id);
        }

        return [
            'success' => false,
            'message' => 'Słownik nie istnieje'
        ];
    }

    public function getDictionaryName($type) {
        return $this->names[$type];
    }

    public function selectDictionaryAndSelectAll($type) {
        $records = null;
        if (array_key_exists($type, $this->repositories)) {
            $records = $this->repositories[$type]->getAllRecords();
        }

        return $records;
    }

    public function selectDictionaryAndselectById($type, $id) {
        $record = null;
        if (array_key_exists($type, $this->repositories)) {
            $record = $this->repositories[$type]->getRecordById($id);
        }

        return $record;
    }
    public function selectDictionaryAndSelectByValue($type, $value) {
        $record = null;
        if (array_key_exists($type, $this->repositories)) {
            $record = $this->repositories[$type]->getRecordByValue($value);
        }

        return $record;
    }

    public function getKeysFromResult($records) {
        return array_keys($records[0]);
    }

    public function hasDefaultValue($type) {
        if ($type == 'duration' || $type == 'privileges') {
            return true;
        }
        return false;
    }
}