<?php

namespace App\Models;

use PDO;

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByUsername($username)
    {
        $sql = "
        SELECT *
        FROM users
        WHERE username = :username
        LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'username'=>$username
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}