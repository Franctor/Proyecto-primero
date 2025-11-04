<?php
namespace repositories;
use PDO;
class Connection{
    private static $conn = null;

    public static function getConnection(){
        if (self::$conn === null) {
            self::$conn = new PDO('mysql:host=localhost;dbname=mydb', 'root', 'root');
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$conn;
    }
}