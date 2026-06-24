<?php

namespace App\Models;

use PDO;

class Employee
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Employees
    |--------------------------------------------------------------------------
    */

    public function getAll()
    {
        $sql = "
        SELECT
            employees.*,
            departments.department_name
        FROM employees
        LEFT JOIN departments
            ON employees.department_id = departments.id
        ORDER BY employees.id DESC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Employee
    |--------------------------------------------------------------------------
    */

    public function create(array $data)
    {
        $sql = "
        INSERT INTO employees
        (
            employee_code,
            department_id,
            first_name,
            last_name,
            designation,
            email,
            mobile,
            status
        )
        VALUES
        (
            :employee_code,
            :department_id,
            :first_name,
            :last_name,
            :designation,
            :email,
            :mobile,
            :status
        )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Employee By ID
    |--------------------------------------------------------------------------
    */

    public function getById($id)
    {
        $sql = "
        SELECT *
        FROM employees
        WHERE id=:id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id'=>$id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Employee
    |--------------------------------------------------------------------------
    */

    public function update($id, array $data)
    {
        $sql = "
        UPDATE employees
        SET
            employee_code=:employee_code,
            department_id=:department_id,
            first_name=:first_name,
            last_name=:last_name,
            designation=:designation,
            email=:email,
            mobile=:mobile,
            status=:status
        WHERE id=:id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            'employee_code'=>$data['employee_code'],
            'department_id'=>$data['department_id'],
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'designation'=>$data['designation'],
            'email'=>$data['email'],
            'mobile'=>$data['mobile'],
            'status'=>$data['status'],
            'id'=>$id

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Employee
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $sql = "
        DELETE FROM employees
        WHERE id=:id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id'=>$id
        ]);
    }

}