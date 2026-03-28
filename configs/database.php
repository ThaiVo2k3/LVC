<?php
class Database
{
    private static $conn;

    public static function connectPDO()
    {
        if (self::$conn) {
            return self::$conn;
        }

        try {
            if (!class_exists('PDO')) {
                throw new Exception('PDO not supported');
            }

            $driver = Env::get('DB_DRIVER');
            $host   = Env::get('DB_HOST');
            $dbname = Env::get('DB_NAME');
            $user   = Env::get('DB_USER');
            $pass   = Env::get('DB_PASS');

            $dsn = "$driver:host=$host;dbname=$dbname;charset=utf8";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            self::$conn = new PDO($dsn, $user, $pass, $options);
        } catch (Throwable $e) {
            throw new Exception('Database connection failed', 500);
        }
        return self::$conn;
    }
}
