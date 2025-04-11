<?php
namespace App\service;

class AccessHandler {
    public function __construct() {
        $this->startSession();
    }

    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getUserPrivilege() {
        return $_SESSION['privilege'] ?? "";
    }
    public function getUserLogin() {
        return $_SESSION['login'] ?? "";
    }
    
    public function isNoneLogged() {
        return $this->getUserPrivilege() == "";
    }

    public function isClientLogged() {
        return $this->getUserPrivilege() == 2;
    }

    public function isWorkerLogged() {
        return $this->getUserPrivilege() == 3;
    }

    public function isAdminLogged() {
        return $this->getUserPrivilege() == 4;
    }
}