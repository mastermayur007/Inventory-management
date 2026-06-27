<?php

namespace App\Models;

use PDO;
use PDOException;

class AssetAssignment
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get All Assignments
     */
    public function getAll()
    {
        $sql = "
            SELECT
                aa.*,
                a.asset_tag,
                a.asset_name,
                e.employee_code,
                CONCAT(e.first_name,' ',e.last_name) AS employee_name

            FROM asset_assignments aa

            INNER JOIN assets a
                ON aa.asset_id = a.id

            INNER JOIN employees e
                ON aa.employee_id = e.id

            ORDER BY aa.id DESC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get Assignment By ID
     */
    public function getById($id)
    {
        $sql = "
            SELECT
                aa.*,
                a.asset_tag,
                a.asset_name,
                e.employee_code,
                CONCAT(e.first_name,' ',e.last_name) AS employee_name

            FROM asset_assignments aa

            INNER JOIN assets a
                ON aa.asset_id = a.id

            INNER JOIN employees e
                ON aa.employee_id = e.id

            WHERE aa.id = :id
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check Asset Already Assigned
     */
    public function isAssetAssigned($assetId)
    {
        $sql = "
            SELECT COUNT(*)

            FROM asset_assignments

            WHERE asset_id = :asset_id

            AND status = 'Assigned'
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'asset_id' => $assetId
        ]);

        return $stmt->fetchColumn() > 0;
    }

}