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

    /*
    |--------------------------------------------------------------------------
    | Get All Assignments
    |--------------------------------------------------------------------------
    */

    public function getAll()
    {
        $sql = "
        SELECT

            aa.*,

            a.asset_tag,
            a.asset_name,
            a.brand,
            a.model,

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

    /*
    |--------------------------------------------------------------------------
    | Get Assignment By ID
    |--------------------------------------------------------------------------
    */

    public function getById($id)
    {
        $sql = "

        SELECT

            aa.*,

            a.asset_tag,
            a.asset_name,
            a.brand,
            a.model,

            e.employee_code,
            CONCAT(e.first_name,' ',e.last_name) AS employee_name

        FROM asset_assignments aa

        INNER JOIN assets a
            ON aa.asset_id = a.id

        INNER JOIN employees e
            ON aa.employee_id = e.id

        WHERE aa.id=:id

        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([

            'id'=>$id

        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Check Asset Already Assigned
    |--------------------------------------------------------------------------
    */

    public function isAssetAssigned($assetId)
    {
        $stmt = $this->db->prepare(

            "SELECT COUNT(*)

            FROM asset_assignments

            WHERE asset_id=:asset_id

            AND status='Assigned'"

        );

        $stmt->execute([

            'asset_id'=>$assetId

        ]);

        return $stmt->fetchColumn() > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Assign Asset
    |--------------------------------------------------------------------------
    */

    public function assignAsset(array $data)
    {
        try
        {

            $this->db->beginTransaction();

            $stmt = $this->db->prepare("

            INSERT INTO asset_assignments
            (

                asset_id,
                employee_id,
                assigned_by,
                assigned_date,
                expected_return_date,
                status,
                remarks

            )

            VALUES
            (

                :asset_id,
                :employee_id,
                :assigned_by,
                :assigned_date,
                :expected_return_date,
                'Assigned',
                :remarks

            )

            ");

            $stmt->execute([

                'asset_id'=>$data['asset_id'],

                'employee_id'=>$data['employee_id'],

                'assigned_by'=>$data['assigned_by'],

                'assigned_date'=>$data['assigned_date'],

                'expected_return_date'=>$data['expected_return_date'] ?: null,

                'remarks'=>$data['remarks']

            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Asset Status
            |--------------------------------------------------------------------------
            */

            $asset = $this->db->prepare("

                UPDATE assets

                SET status='Assigned'

                WHERE id=:id

            ");

            $asset->execute([

                'id'=>$data['asset_id']

            ]);

            $this->db->commit();

            return true;

        }
        catch(PDOException $e)
        {

            if($this->db->inTransaction())
            {
                $this->db->rollBack();
            }

            return false;
        }
    }
        /*
    |--------------------------------------------------------------------------
    | Update Assignment
    |--------------------------------------------------------------------------
    */

    public function update($id, array $data)
    {
        try
        {
            $stmt = $this->db->prepare("

                UPDATE asset_assignments

                SET

                    employee_id = :employee_id,
                    assigned_by = :assigned_by,
                    assigned_date = :assigned_date,
                    expected_return_date = :expected_return_date,
                    remarks = :remarks

                WHERE id = :id

            ");

            return $stmt->execute([

                'employee_id'          => $data['employee_id'],
                'assigned_by'          => $data['assigned_by'],
                'assigned_date'        => $data['assigned_date'],
                'expected_return_date' => $data['expected_return_date'],
                'remarks'              => $data['remarks'],
                'id'                   => $id

            ]);

        }
        catch(PDOException $e)
        {
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Return Asset
    |--------------------------------------------------------------------------
    */

    public function returnAsset($id, array $data)
    {
        try
        {
            $this->db->beginTransaction();

            $assignment = $this->getById($id);

            if(!$assignment)
            {
                return false;
            }

            $stmt = $this->db->prepare("

                UPDATE asset_assignments

                SET

                    actual_return_date = :actual_return_date,
                    status = 'Returned',
                    remarks = :remarks

                WHERE id = :id

            ");

            $stmt->execute([

                'actual_return_date' => $data['actual_return_date'],
                'remarks'            => $data['remarks'],
                'id'                 => $id

            ]);

            $asset = $this->db->prepare("

                UPDATE assets

                SET status='Available'

                WHERE id=:id

            ");

            $asset->execute([

                'id'=>$assignment['asset_id']

            ]);

            $this->db->commit();

            return true;
        }
        catch(PDOException $e)
        {
            if($this->db->inTransaction())
            {
                $this->db->rollBack();
            }

            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Assignment
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        try
        {
            $assignment = $this->getById($id);

            if($assignment)
            {
                $asset = $this->db->prepare("

                    UPDATE assets

                    SET status='Available'

                    WHERE id=:id

                ");

                $asset->execute([

                    'id'=>$assignment['asset_id']

                ]);
            }

            $stmt = $this->db->prepare("

                DELETE FROM asset_assignments

                WHERE id=:id

            ");

            return $stmt->execute([

                'id'=>$id

            ]);

        }
        catch(PDOException $e)
        {
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Get Active Assignments
    |--------------------------------------------------------------------------
    */

    public function getAssignedAssets()
    {
        $stmt = $this->db->query("

            SELECT *

            FROM asset_assignments

            WHERE status='Assigned'

            ORDER BY assigned_date DESC

        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment History
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $stmt = $this->db->query("

            SELECT *

            FROM asset_assignments

            ORDER BY created_at DESC

        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        /*
    |--------------------------------------------------------------------------
    | Search Assignments
    |--------------------------------------------------------------------------
    */

    public function search($keyword)
    {
        $stmt = $this->db->prepare("

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

            WHERE

                a.asset_name LIKE :keyword

                OR

                a.asset_tag LIKE :keyword

                OR

                e.employee_code LIKE :keyword

                OR

                e.first_name LIKE :keyword

                OR

                e.last_name LIKE :keyword

            ORDER BY aa.id DESC

        ");

        $stmt->execute([

            'keyword' => "%{$keyword}%"

        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Assignments By Employee
    |--------------------------------------------------------------------------
    */

    public function getByEmployee($employeeId)
    {
        $stmt = $this->db->prepare("

            SELECT *

            FROM asset_assignments

            WHERE employee_id=:employee_id

            ORDER BY assigned_date DESC

        ");

        $stmt->execute([

            'employee_id'=>$employeeId

        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Assignments By Asset
    |--------------------------------------------------------------------------
    */

    public function getByAsset($assetId)
    {
        $stmt = $this->db->prepare("

            SELECT *

            FROM asset_assignments

            WHERE asset_id=:asset_id

            ORDER BY assigned_date DESC

        ");

        $stmt->execute([

            'asset_id'=>$assetId

        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Statistics
    |--------------------------------------------------------------------------
    */

    public function countAll()
    {
        return $this->db
            ->query("
                SELECT COUNT(*)
                FROM asset_assignments
            ")
            ->fetchColumn();
    }

    public function countAssigned()
    {
        return $this->db
            ->query("
                SELECT COUNT(*)
                FROM asset_assignments
                WHERE status='Assigned'
            ")
            ->fetchColumn();
    }

    public function countReturned()
    {
        return $this->db
            ->query("
                SELECT COUNT(*)
                FROM asset_assignments
                WHERE status='Returned'
            ")
            ->fetchColumn();
    }

}