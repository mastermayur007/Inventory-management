<?php

namespace App\Controllers;

use App\Models\AssetAssignment;

class AssetAssignmentController
{
    private AssetAssignment $assignment;

    public function __construct($db)
    {
        $this->assignment = new AssetAssignment($db);
    }

    /**
     * Display all asset assignments
     */
    public function index()
    {
        return $this->assignment->getAll();
    }

    /**
     * Store a new asset assignment
     */
    public function store(array $data)
    {
        // Check if asset is already assigned
        if ($this->assignment->isAssetAssigned($data['asset_id'])) {

            $_SESSION['error'] = "This asset is already assigned.";

            return false;
        }

        if ($this->assignment->assignAsset($data)) {

            $_SESSION['success'] = "Asset assigned successfully.";

            return true;
        }

        $_SESSION['error'] = "Failed to assign asset.";

        return false;
    }

    /**
     * Get assignment details by ID
     */
    public function getById($id)
    {
        return $this->assignment->getById($id);
    }

    /**
     * Update assignment
     */
    public function update($id, array $data)
    {
        if ($this->assignment->update($id, $data)) {

            $_SESSION['success'] = "Assignment updated successfully.";

            return true;
        }

        $_SESSION['error'] = "Failed to update assignment.";

        return false;
    }

    /**
     * Return assigned asset
     */
    public function returnAsset($id, array $data)
    {
        if ($this->assignment->returnAsset($id, $data)) {

            $_SESSION['success'] = "Asset returned successfully.";

            return true;
        }

        $_SESSION['error'] = "Failed to return asset.";

        return false;
    }

    /**
     * Delete assignment
     */
    public function delete($id)
    {
        if ($this->assignment->delete($id)) {

            $_SESSION['success'] = "Assignment deleted successfully.";

            return true;
        }

        $_SESSION['error'] = "Failed to delete assignment.";

        return false;
    }

    /**
     * Get only active assignments
     */
    public function getAssignedAssets()
    {
        return $this->assignment->getAssignedAssets();
    }

    /**
     * Get assignment history
     */
    public function history()
    {
        return $this->assignment->history();
    }

    /**
     * Search assignments
     */
    public function search($keyword)
    {
        return $this->assignment->search($keyword);
    }

    /**
     * Filter by employee
     */
    public function getByEmployee($employeeId)
    {
        return $this->assignment->getByEmployee($employeeId);
    }

    /**
     * Filter by asset
     */
    public function getByAsset($assetId)
    {
        return $this->assignment->getByAsset($assetId);
    }

    /**
     * Dashboard statistics
     */
    public function dashboard()
    {
        return [

            'totalAssignments' => $this->assignment->countAll(),

            'assignedAssets'   => $this->assignment->countAssigned(),

            'returnedAssets'   => $this->assignment->countReturned()

        ];
    }
}