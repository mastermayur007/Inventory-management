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
            'remarks'         => $data['remarks']

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Asset By ID
    |--------------------------------------------------------------------------
    */
    public function getById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM assets WHERE id=:id"
        );

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
            asset_tag=:asset_tag,
            serial_number=:serial_number,
            asset_name=:asset_name,
            category=:category,
            brand=:brand,
            model=:model,
            vendor_id=:vendor_id,
            purchase_date=:purchase_date,
            warranty_expiry=:warranty_expiry,
            status=:status,
            remarks=:remarks
        WHERE id=:id
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

    /*
    |--------------------------------------------------------------------------
    | Delete Asset
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM assets WHERE id=:id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
public function getByAssetTag($assetTag)
{
    $stmt = $this->db->prepare(
        "SELECT * FROM assets WHERE asset_tag = :asset_tag"
    );

    $stmt->execute([
        'asset_tag' => $assetTag
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
