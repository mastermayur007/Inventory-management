<?php
namespace App\Models;
use PDO;

class Asset
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Assets
    |--------------------------------------------------------------------------
    */
public function getAll()
{
    $sql = "
    SELECT
        assets.*,
        vendors.company_name
    FROM assets
    LEFT JOIN vendors
        ON assets.vendor_id = vendors.id
    ORDER BY assets.id DESC
    ";

    return $this->db
        ->query($sql)
        ->fetchAll(PDO::FETCH_ASSOC);
}


   /*
|--------------------------------------------------------------------------
| Create Asset
|--------------------------------------------------------------------------
*/
public function create(array $data)
{
    $sql = "
    INSERT INTO assets
    (
        asset_tag,
        serial_number,
        asset_name,
        category,
        brand,
        model,
        vendor_id,
        purchase_date,
        warranty_expiry,
        status,
        remarks
    )
    VALUES
    (
        :asset_tag,
        :serial_number,
        :asset_name,
        :category,
        :brand,
        :model,
        :vendor_id,
        :purchase_date,
        :warranty_expiry,
        :status,
        :remarks
    )
    ";

    $stmt = $this->db->prepare($sql);

    $success = $stmt->execute([

        'asset_tag'       => $data['asset_tag'],
        'serial_number'   => $data['serial_number'],
        'asset_name'      => $data['asset_name'],
        'category'        => $data['category'],
        'brand'           => $data['brand'],
        'model'           => $data['model'],
        'vendor_id'       => $data['vendor_id'],
        'purchase_date'   => $data['purchase_date'],
        'warranty_expiry' => $data['warranty_expiry'],
        'status'          => $data['status'],
        'remarks'         => $data['remarks']

    ]);

    if ($success)
    {
        $lastId = $this->db->lastInsertId();

        $assetId = "INL" . str_pad($lastId, 6, "0", STR_PAD_LEFT);

        $update = $this->db->prepare("
            UPDATE assets
            SET asset_id = :asset_id
            WHERE id = :id
        ");

        $update->execute([

            'asset_id' => $assetId,

            'id' => $lastId

        ]);
    }

    return $success;
}
/*
|--------------------------------------------------------------------------
| Get Asset By ID
|--------------------------------------------------------------------------
*/
public function getById($id)
{
    $sql = "
        SELECT
            assets.*,
            vendors.company_name
        FROM assets
        LEFT JOIN vendors
            ON assets.vendor_id = vendors.id
        WHERE assets.id = :id
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
/*
|--------------------------------------------------------------------------
| Update Asset
|--------------------------------------------------------------------------
*/
public function update($id, array $data)
{
    $sql = "
    UPDATE assets
    SET
        asset_tag = :asset_tag,
        serial_number = :serial_number,
        asset_name = :asset_name,
        category = :category,
        brand = :brand,
        model = :model,
        vendor_id = :vendor_id,
        purchase_date = :purchase_date,
        warranty_expiry = :warranty_expiry,
        status = :status,
        remarks = :remarks
    WHERE id = :id
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([

        'asset_tag'       => $data['asset_tag'],
        'serial_number'   => $data['serial_number'],
        'asset_name'      => $data['asset_name'],
        'category'        => $data['category'],
        'brand'           => $data['brand'],
        'model'           => $data['model'],
        'vendor_id'       => $data['vendor_id'],
        'purchase_date'   => $data['purchase_date'],
        'warranty_expiry' => $data['warranty_expiry'],
        'status'          => $data['status'],
        'remarks'         => $data['remarks'],
        'id'              => $id

    ]);
}
}
