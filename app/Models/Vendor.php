<?php
namespace App\Models;
use PDO;
class Vendor
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Vendors
    |--------------------------------------------------------------------------
    */
    public function getAll()
    {
        $sql = "
        SELECT *
        FROM vendors
        ORDER BY id DESC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Vendor
    |--------------------------------------------------------------------------
    */
    public function create(array $data)
    {
        $sql = "
        INSERT INTO vendors
        (
            vendor_code,
            company_name,
            contact_person,
            email,
            phone,
            address,
            status
        )
        VALUES
        (
            :vendor_code,
            :company_name,
            :contact_person,
            :email,
            :phone,
            :address,
            :status
        )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            'vendor_code' => $data['vendor_code'],
            'company_name' => $data['company_name'],
            'contact_person' => $data['contact_person'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'status' => $data['status']

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Vendor By ID
    |--------------------------------------------------------------------------
    */
    public function getById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM vendors WHERE id=:id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Vendor
    |--------------------------------------------------------------------------
    */
    public function update($id, array $data)
    {
        $sql = "
        UPDATE vendors
        SET
            vendor_code=:vendor_code,
            company_name=:company_name,
            contact_person=:contact_person,
            email=:email,
            phone=:phone,
            address=:address,
            status=:status
        WHERE id=:id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            'vendor_code' => $data['vendor_code'],
            'company_name' => $data['company_name'],
            'contact_person' => $data['contact_person'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'status' => $data['status'],
            'id' => $id

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Vendor
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM vendors WHERE id=:id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }

public function getDropdown()
{
    $sql = "
        SELECT
            id,
            company_name
        FROM vendors
        WHERE status = 'Active'
        ORDER BY company_name ASC
    ";

    return $this->db
        ->query($sql)
        ->fetchAll(PDO::FETCH_ASSOC);
}


}

