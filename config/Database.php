<?php
class Database
{
    public $host = "localhost";
    public $username = "root";
    public $password = "";
    public $databaseName = "eithercookid_db";

    public function connect(): ?PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->databaseName};charset=utf8mb4";

        $connection = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
        ]);

        return $connection ?: null;
    }
}