<?php
namespace repositories;

use PDO;
use PDOException;

class Connection
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO('mysql:host=localhost;dbname=mydb', 'root', 'root');
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log("Error de conexión: " . $e->getMessage());
                throw $e;
            }
        }
        return self::$conn;
    }
}
