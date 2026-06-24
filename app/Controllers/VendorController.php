<?php

namespace App\Controllers;

use App\Models\Vendor;

class VendorController
{
    private Vendor $vendor;

    public function __construct($db)
    {
        $this->vendor = new Vendor($db);
    }

    public function index()
    {
        return $this->vendor->getAll();
    }

    public function store(array $data)
    {
        return $this->vendor->create($data);
    }

    public function getById($id)
    {
        return $this->vendor->getById($id);
    }

    public function update($id,array $data)
    {
        return $this->vendor->update($id,$data);
    }

    public function delete($id)
    {
        return $this->vendor->delete($id);
    }
    
public function getDropdown()
{
    return $this->vendor->getDropdown();
}

}