<?php
namespace App\service;

use App\repository\dictionary\DurationRepository;
use App\repository\dictionary\RoomStateRepository;
use App\repository\dictionary\InstrumentStateRepository;
use App\repository\dictionary\PaymentMethodRepository;
use App\repository\LecturerRepository;
use App\repository\LectureRepository;
use App\repository\RoomRepository;
use App\repository\InstrumentRepository;

use DateTime;

class ValidationHandler {
    private $validators = [
        'login' => 'isCorrectLogin',
        'password' => 'isCorrectPassword',
        'name' => 'isCorrectName',
        'surname' => 'isCorrectSurname',
        'email' => 'isCorrectEmail',
        'phone' => 'isCorrectPhoneNumber',
        'city' => 'isCorrectCity',
        'street' => 'isCorrectStreet',
        'house' => 'isCorrectHouseNumber',
        'apartment' => 'isCorrectApartmentNumber',
        'privilege' => 'isCorrectPrivilege',
        'lecturesMade' => 'isCorrectNumber',
        'payment' => 'isCorrectPayment',
        'capacity' => 'isCorrectNumber',
        'stateRoom' => 'isCorrectRoomState',
        'stateInstrument' => 'isCorrectInstrumentState',
        'position' => 'isCorrectValue',
        "title" => 'isCorrectValue', 
        "description" => 'isCorrectDescription',
        "lecturerId" => 'isCorrectLecturerId', 
        "durationId" => 'isCorrectDurationId', 
        "content" => 'isCorrectContentFile', 
        "price" => 'isCorrectPrice', 
        "date" => 'isCorrectDateTime',
        "roomId" => 'isCorrectRoomNumber',
        "value" => 'isCorrectValue'
    ];
    private $validationMessages = [
        'login' => 'Nieprawidłowy login (min. 4 maks. 16 znaków, litery łacińskie i/lub liczby)',
        'password' => 'Nieprawidłowe hasło (min. 8 maks. 16 znaków)',
        'name' => 'Imię jest za długie lub za krótkie lub są spację',
        'surname' => 'Nazwisko jest za długie lub za krótkie lub są spację',
        'email' => 'Nieprawidłowy email',
        'phone' => 'Nieprawidłowy numer komórkowy',
        'city' => 'Nieprawidłowe miasto',
        'street' => 'Nieprawidłowa ulica',
        'house' => 'Nieprawidłowy numer budynku',
        'apartment' => 'Nieprawidłowy numer mieszkania',
        'privilege' => 'Nieprawidłowa wartość uprawnienia',
        'lecturesMade' => 'Nieprawidłowa wartość przeprowadzonych wykładow',
        'payment' => 'Nieprawidłowa wartość wynagrodzenia',
        'capacity' => 'Nieprawidłowa wartość pojemności',
        'stateRoom' => 'Nieprawidłowy stan sali',
        'stateInstrument' => 'Nieprawidłowy stan sprzętu',
        'position' => 'Nieprawidłowy zawód',
        "title" => 'Nieprawidłowy tytuł', 
        "description" => 'Nieprawidłowa długość opisu (min. 20 maks. 100 znaków, litery łacińskie i/lub liczby)', 
        "lecturerId" => 'Nierawidłowy wykładowca', 
        "durationId" => 'Nieprawidłowy czas trwania', 
        "content" => 'Nieprawidłowy plik treści', 
        "price" => 'Nieprawidłowa cena', 
        "date" => 'Nieprawidłowa data lub czas',
        "roomId" => 'Nieprawidłowy numer sali',
        "value" => 'Nieprawidłowa wartość'
    ];
    private $allowedNull = ['apartment', 'phone', 'description', 'lecturerId', 'durationId', 'content', 'price', 'date', 'roomId'];
    private $dictionaryNames = [
        'duration', 'payment', 'privileges', 'workhour', 'instrumentState'
    ];

    private function isCorrectLogin($login) {
        return preg_match('/^[a-zA-Z0-9_]{4,16}$/', $login);
    }

    public function isCorrectPassword($password) {
        return preg_match('/^[^\s]{8,16}$/', $password);
    }

    private function isCorrectName($name) {
        return preg_match('/^[^\s]{3,50}$/', $name);
    }

    private function isCorrectSurname($surname) {
        return preg_match('/^[^\s]{3,60}$/', $surname);
    }

    private function isCorrectEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 75;
    }

    private function isCorrectPhoneNumber($phone) {
        return preg_match('/^\d{9}$/', $phone);
    }

    private function isCorrectCity($city) {
        return preg_match('/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻA-Za-z0-9\s\-.]{3,50}$/', $city);
    }

    private function isCorrectStreet($street) {
        return preg_match('/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻA-Za-z0-9\s\-.]{3,60}$/', $street);
    }

    private function isCorrectHouseNumber($houseNumber) {
        return preg_match('/^[A-Za-z0-9]{1,10}$/', $houseNumber);
    }

    private function isCorrectApartmentNumber($apartmentNumber) {
        return preg_match('/^[A-Za-z0-9]{1,10}$/', $apartmentNumber);
    }

    private function isCorrectPrivilege($privilege) {
        return $privilege == 2 || $privilege == 3 || $privilege == 4;
    }

    private function isCorrectNumber($number) {
        return preg_match('/^\d$|^[1-9]\d+$/', $number);
    }

    private function isCorrectPayment($number) {
        return preg_match('/^[1-9]\d{2,}$/', $number);
    }

    private function isCorrectPrice($number) {
        return preg_match('/^[1-9]\d{1,}$/', $number);
    }

    public function isCorrectDictionaryName($type) {
        foreach ($this->dictionaryNames as $name) {
            if ($type == $name) {
                return true;
            }
        }

        return false;
    }
    public function isCorrectValue($value) {
        return preg_match('/^(?! )[ąćęłńóśźżĄĆĘŁŃÓŚŹŻA-Za-z0-9\s\-.]{1,50}$/', $value);
    }

    public function isCorrectDescription($description) {
        return preg_match('/^[ąćęłńóśźżĄĆĘŁŃÓŚŹŻA-Za-z0-9\s\-.]{20,100}$/', $description);
    }

    public function isCorrectContentFile($contentFile) {
        if (isset($contentFile) && $contentFile['error'] === UPLOAD_ERR_OK) {
            $filePath = $contentFile['tmp_name'];
    
            if (mime_content_type($filePath) !== 'application/pdf') {
                return false;
            }
    
            $extension = pathinfo($contentFile['name'], PATHINFO_EXTENSION);
            if (strtolower($extension) !== 'pdf') {
                return false;
            }
    
            return true;
        } else {
            return false;
        }
    }

    public function isCorrectLecturerId($id) {
        $lecturerRepo = new LecturerRepository();
        $lecturer = $lecturerRepo->getLecturerById($id);

        return ($lecturer != null);
    }

    public function isCorrectDurationId($id) {
        $durationRepo = new DurationRepository();
        $duration = $durationRepo->getRecordById($id);

        return ($duration != null);
    }

    public function isCorrectRoomNumber($id) {
        $roomRepo = new RoomRepository();
        $room = $roomRepo->getRoomByNumber($id);

        return ($room != null);
    }

    public function isCorrectDateTime($dateTimeString) {
        if ($dateTimeString == " ") {
            return true;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $dateTimeString)) {
            $dateTimeString .= ':00';
        }
    
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString);
        if (!$dateTime || $dateTime->format('Y-m-d H:i:s') !== $dateTimeString) {
            return false;
        }
    
        $now = new DateTime();
    
        if ($dateTime->format('Y-m-d') === $now->format('Y-m-d')) {
            if ($dateTime < $now) {
                return false;
            }
        }
        return $dateTime >= $now;
    }

    public function isCorrectLectureId($id) {
        $lectureRepo = new LectureRepository();
        $lecture = $lectureRepo->getLectureById($id);

        return ($lecture != null);
    }

    public function isCorrectPaymentMethod($methodId) {
        $paymentRepo = new PaymentMethodRepository();
        $payment = $paymentRepo->getRecordById($methodId);

        return ($payment != null);
    }

    public function validateInputs($inputs) {
        $errorMessages = [];

        foreach ($inputs as $field => $value) {
            if (isset($this->validators[$field])) {
                $validatorMethod = $this->validators[$field];
                if (empty($value) && in_array($field, $this->allowedNull)) {
                    continue;
                }
                if (!$this->$validatorMethod($value)) {
                    $errorMessages[] = $this->validationMessages[$field];
                }
            }
        }

        return $errorMessages;
    }
}