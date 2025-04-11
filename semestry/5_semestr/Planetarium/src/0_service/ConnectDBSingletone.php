<?php 
namespace App\service;
use PDO;
use PDOException;

class ConnectDB {
    private static $instance = null;
    private $pdo;
    private $exception;

    private $adminConnectionInfo = [
        "host" => "localhost",
        "db" => "PlanetariumDB",
        "user" => 'admin',
        "password" => 'admin'
    ];

    private $guestConnectionInfo = [
        "host" => "localhost",
        "db" => "PlanetariumDB",
        "user" => 'GOSC',
        "password" => 'gosc'
    ];

    private function __construct($connectionUser){
        $user = ($connectionUser == "admin") ? $this->adminConnectionInfo : $this->guestConnectionInfo;
        $server = $user['host'];
        $db = $user['db'];
        $login = $user['user'];
        $password = $user['password'];

        try {
            $this->pdo = new PDO("sqlsrv:Server=$server;Database=$db", $login, $password);
        }
        catch (PDOException $exception) {
            $this->pdo = null;
            $this->exception = $exception;
        }
    }

    public static function getInstance($connectionUser){
        if(!self::$instance){
            self::$instance = new ConnectDB($connectionUser);
        }

        return self::$instance;
    }

    public function getConnection(){
        return $this->pdo;
    }
    public function getException() {
        return $this->exception;
    }
}