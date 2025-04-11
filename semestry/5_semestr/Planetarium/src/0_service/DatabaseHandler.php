<?php 
namespace App\service;
use PDO;
use PDOException;

class DatabaseHandler {
    protected static $pdo;
    private $exception;

    private $adminConnectionInfo = [
        "host" => "localhost",
        "db" => "PlanetariumDB",
        "user" => 'ADMINISTRATOR',
        "password" => 'admin'
    ];

    private $guestConnectionInfo = [
        "host" => "localhost",
        "db" => "PlanetariumDB",
        "user" => 'GOSC',
        "password" => 'gosc'
    ];

    private $currentUser;
    private $isAdmin;

    public function createConnection() {
        $server = $this->currentUser['host'];
        $db = $this->currentUser['db'];
        $login = $this->currentUser['user'];
        $password = $this->currentUser['password'];

        try {
            self::$pdo = new PDO("sqlsrv:Server=$server;Database=$db", $login, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $exception) {
            self::$pdo = null;
            $this->exception = $exception;
        }
    }

    public function getConnection(){
        return self::$pdo;
    }
    public function getException() {
        return $this->exception;
    }
    public function getIsAdmin() {
        return $this->isAdmin;
    }

    public function setUser($user) {
        $this->currentUser = ($user == "admin") ? $this->adminConnectionInfo : $this->guestConnectionInfo;
        $this->isAdmin = ($user == "admin");
    }

    public function establishConnection($user) {
        $this->setUser($user);
        $this->createConnection();
    }
}