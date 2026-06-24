<?php

namespace App\Controllers;

use App\Models\Asset;

class AssetController
{
    private Asset $asset;

    public function __construct($db)
    {
        $this->asset = new Asset($db);
    }

    public function index()
    {
        return $this->asset->getAll();
    }

    public function store(array $data)
    {
        return $this->asset->create($data);
    }

    public function getById($id)
    {
        return $this->asset->getById($id);
    }

    public function update($id, array $data)
    {
        return $this->asset->update($id, $data);
    }

    public function delete($id)
    {
        return $this->asset->delete($id);
    }
public function getByAssetTag($assetTag)
{
    return $this->asset->getByAssetTag($assetTag);
}


}
