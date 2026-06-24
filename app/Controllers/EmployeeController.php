<?php

namespace App\Controllers;

use App\Models\Employee;

class EmployeeController
{
    private Employee $employee;

    public function __construct($db)
    {
        $this->employee = new Employee($db);
    }

    public function index()
    {
        return $this->employee->getAll();
    }

    public function store(array $data)
    {
        return $this->employee->create($data);
    }

    public function getById($id)
    {
        return $this->employee->getById($id);
    }

    public function update($id,array $data)
    {
        return $this->employee->update($id,$data);
    }

    public function delete($id)
    {
        return $this->employee->delete($id);
    }

}