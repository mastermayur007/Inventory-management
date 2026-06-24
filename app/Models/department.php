<?php

namespace App\Models;

use PDO;

class Department
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Departments
    |--------------------------------------------------------------------------
    */
    public function getAll()
    {
        $sql = "
            SELECT *
            FROM departments
            ORDER BY id DESC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Department
    |--------------------------------------------------------------------------
    */
    public function create(array $data)
    {
        $sql = "
            INSERT INTO departments
            (
                department_name,
                department_code,
                description,
                status
            )
            VALUES
            (
                :department_name,
                :department_code,
                :description,
                :status
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'department_name' => $data['department_name'],
            'department_code' => $data['department_code'],
            'description' => $data['description'],
            'status' => $data['status']
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Department By ID
    |--------------------------------------------------------------------------
    */
    public function getById($id)
    {
        $sql = "
            SELECT *
            FROM departments
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Department
    |--------------------------------------------------------------------------
    */
    public function update($id, array $data)
    {
        $sql = "
            UPDATE departments
            SET
                department_name = :department_name,
                department_code = :department_code,
                description = :description
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'department_name' => $data['department_name'],
            'department_code' => $data['department_code'],
            'description' => $data['description'],
            'id' => $id
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Department
    |--------------------------------------------------------------------------
    */
public function delete($id)
{
    $sql = "DELETE FROM departments WHERE id = :id";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        'id' => $id
    ]);
}
public function getDropdown()
{
    $sql = "
        SELECT id, department_name
        FROM departments
        WHERE status = :status
        ORDER BY department_name
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'status' => 'Active'
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
