<?php

class DB{
    private static $instance;
    private static $dsn = 'mysql:host=localhost;dbname=monfab';
    private static $user = 'root';
    private static $password = '';

    private function __construct(){
        try {
            self::$instance = new PDO(self::$dsn, self::$user, self::$password);
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
        }
    }

    public static function getInstance() {
        if(!self::$instance) {
            new DB();
        }
        return self::$instance;
    }
}

?>