<?php
namespace App\service;

class SessionHandler {
    public function __construct() {
    }

    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function finishSession() {
        $this->startSession();
        
        session_unset();
        session_destroy();
    }

    public function getUserValueByKey($key) {
        $this->startSession();
        return $_SESSION[$key] ?? "";
    }

    public function setUserDataInSession($user) {
        $_SESSION['userId'] = $user['Id'];
        $_SESSION['login'] = $user['Login'];
        $_SESSION['privilege'] = $user['Przywileje'];
    }

    public function updateUserDataInSession($inputs) {
        foreach ($inputs as $name => $value) {
            $_SESSION[$name] = $value;
        }
    }
    
    public function getUserData() {
        $sessionValues = [];

        $sessionValues['Id'] = $_SESSION['userId'];
        $sessionValues['Login'] = $_SESSION['login'];
        $sessionValues['Przywileje'] = $_SESSION['privilege'];

        return $sessionValues;
    }

    public function setTicketId($id) {
        $_SESSION['ticketId'] = $id;
    }

    public function getCurrentTicketId() {
        return $_SESSION['ticketId'] ?? "";
    }

    public function removeTicket() {
        unset($_SESSION['ticketId']);
    }
}