<?php

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $host = "localhost";
        $dbname = "itam";
        $username = "mayur";
        $password = "Mayur@123";

        try {

            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password
            );

            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {

            die($e->getMessage());

        }
    }

    public function connection()
    {
        return $this->pdo;
    }
}