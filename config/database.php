<?php

class Database
{
    private string $host = "localhost";
    private string $dbname = "itam";
    private string $username = "root";
    private string $password = "";

    public function connect()
    {
        try
        {
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $pdo;
        }
        catch(PDOException $e)
        {
            die(
                "Database Connection Failed : "
                . $e->getMessage()
            );
        }
    }
}