<?php
namespace models;

use PDO;
use PDOException;

class DB {
    private static $instance;
    private static $dsn = 'mysql:host=localhost;dbname=monfab';
    private static $user = 'root';
    private static $password = '';

    private function __construct() {
        try {
            self::$instance = new PDO(self::$dsn, self::$user, self::$password);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log('Error de conexión: ' . $e->getMessage());
            echo 'Error de conexión: ' . $e->getMessage();
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            new DB();
        }
        return self::$instance;
    }
}
?>
