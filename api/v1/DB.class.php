<?php

class DB {
    private static $instance = NULL;

    private function __construct(){}

    private function __clone(){}

    public static function getInstance(){
        if (!self::$instance) {
            try {
                self::$instance = new PDO("mysql:host=localhost;dbname=uhealth;charset=utf8", 'root', '');
            } catch (PDOException $e) {
                error("404.1");
            }
        }
        return self::$instance;
    }
}
?>
