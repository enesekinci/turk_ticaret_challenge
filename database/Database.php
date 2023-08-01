<?php

namespace Database;

class Database
{
    protected static $instance;
    protected $connection;
    protected $host;
    protected $username;
    protected $password;
    protected $database;
    protected $charset;
    protected $dsn;

    protected function __construct()
    {
        $this->host = \DB_HOST;
        $this->username = \DB_USER;
        $this->password = \DB_PASSWORD;
        $this->database = \DB_NAME;
        $this->charset = 'utf8mb4';

        $this->dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        if ($this->connection === null) {
            $this->connection =  new \PDO($this->dsn, $this->username, $this->password);
        }
        return $this->connection;
    }

    public function __destruct()
    {
        $this->connection = null;
    }
}
