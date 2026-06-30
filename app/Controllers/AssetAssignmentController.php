<?php

namespace App\Controllers;

use App\Models\AssetAssignment;

class AssetAssignmentController
{
    private $db;
    private AssetAssignment $assignment;

    public function __construct()
    {
        require_once __DIR__ . '/../../config/database.php';

        $database = new \Database();
        $this->db = $database->connect();

        $this->assignment = new AssetAssignment($this->db);
    }

    public function index()
    {
        return $this->assignment->getAll();
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?page=asset-assignments");
            exit;
        }

        $data = [
            'asset_id' => $_POST['asset_id'] ?? null,
            'employee_id' => $_POST['employee_id'] ?? null,
            'assigned_by' => $_POST['assigned_by'] ?? '',
            'assigned_date' => $_POST['assigned_date'] ?? date('Y-m-d'),
            'expected_return_date' => $_POST['expected_return_date'] ?? null,
            'remarks' => $_POST['remarks'] ?? ''
        ];

        if (!$data['asset_id'] || !$data['employee_id']) {
            $_SESSION['error'] = 'Please select both Asset and Employee.';
            header("Location: ?page=asset-assignment-create");
            exit;
        }

        if ($this->assignment->isAssetAssigned($data['asset_id'])) {
            $_SESSION['error'] = 'This asset is already assigned.';
            header("Location: ?page=asset-assignment-create");
            exit;
        }

        if ($this->assignment->assignAsset($data)) {
            $_SESSION['success'] = 'Asset assigned successfully.';
            header("Location: ?page=asset-assignments");
            exit;
        }

        $_SESSION['error'] = 'Failed to assign asset.';
        header("Location: ?page=asset-assignment-create");
        exit;
    }

    public function getById($id)
    {
        return $this->assignment->getById($id);
    }

    public function update($id = null, array $data = [])
    {
        if ($id === null) {
            $id = $_POST['id'] ?? null;
            $data = $_POST;
        }

        if ($this->assignment->update($id, $data)) {
            $_SESSION['success'] = 'Assignment updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update assignment.';
        }

        header("Location: ?page=asset-assignments");
        exit;
    }

    public function returnAsset($id = null, array $data = [])
    {
        if ($id === null) {
            $id = $_POST['id'] ?? null;
            $data = $_POST;
        }

        if ($this->assignment->returnAsset($id, $data)) {
            $_SESSION['success'] = 'Asset returned successfully.';
        } else {
            $_SESSION['error'] = 'Failed to return asset.';
        }

        header("Location: ?page=asset-assignments");
        exit;
    }

    public function delete($id = null)
    {
        if ($id === null) {
            $id = $_GET['id'] ?? null;
        }

        if ($this->assignment->delete($id)) {
            $_SESSION['success'] = 'Assignment deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete assignment.';
        }

        header("Location: ?page=asset-assignments");
        exit;
    }

    public function getAssignedAssets()
    {
        return $this->assignment->getAssignedAssets();
    }

    public function history()
    {
        return $this->assignment->history();
    }

    public function search($keyword)
    {
        return $this->assignment->search($keyword);
    }

    public function getByEmployee($employeeId)
    {
        return $this->assignment->getByEmployee($employeeId);
    }

    public function getByAsset($assetId)
    {
        return $this->assignment->getByAsset($assetId);
    }

    public function dashboard()
    {
        return [
            'totalAssignments' => $this->assignment->countAll(),
            'assignedAssets' => $this->assignment->countAssigned(),
            'returnedAssets' => $this->assignment->countReturned()
        ];
    }
}
