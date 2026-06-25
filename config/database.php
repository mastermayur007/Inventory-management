
<?php

class Database
{
    private string $host = "localhost";
    private string $dbname = "itam";
    private string $username = "mayur";
    private string $password = "YOUR_DATABASE_PASSWORD";

    public function connect()
    {
        try
        {
            $pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
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
