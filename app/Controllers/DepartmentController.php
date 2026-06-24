<?php

namespace App\Controllers;

use App\Models\Department;

class DepartmentController
{
    private Department $department;

    public function __construct($db)
    {
        $this->department = new Department($db);
    }

    public function index()
    {
        return $this->department->getAll();
    }

    public function store(array $data)
    {
        return $this->department->create($data);
    }

    public function getById($id)
    {
        return $this->department->getById($id);
    }

    public function update($id, array $data)
    {
        return $this->department->update($id, $data);
    }

   public function delete($id)
{
    return $this->department->delete($id);
}
}