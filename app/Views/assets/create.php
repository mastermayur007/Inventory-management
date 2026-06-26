
<?php

use App\Controllers\AssetController;
use App\Controllers\VendorController;

$db = new Database();
$conn = $db->connect();

$assetController = new AssetController($conn);
$vendorController = new VendorController($conn);

$vendors = $vendorController->getDropdown();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $checkAsset = $conn->prepare(
        "SELECT * FROM assets WHERE asset_tag = ?"
    );

    $checkAsset->execute([
        $_POST['asset_tag']
    ]);

    $existingAsset = $checkAsset->fetch(PDO::FETCH_ASSOC);

    if ($existingAsset)
    {
        $error = "
        Asset already exists.<br>
        Asset Tag: {$existingAsset['asset_tag']}<br>
        Asset Name: {$existingAsset['asset_name']}<br>
        Serial Number: {$existingAsset['serial_number']}<br>
        Status: {$existingAsset['status']}
        ";
    }
    else
    {
        $assetController->store([

            'asset_tag'       => $_POST['asset_tag'],
            'serial_number'   => $_POST['serial_number'],
            'asset_name'      => $_POST['asset_name'],
            'category'        => $_POST['category'],
            'brand'           => $_POST['brand'],
            'model'           => $_POST['model'],
            'vendor_id'       => $_POST['vendor_id'],
            'purchase_date'   => $_POST['purchase_date'],
            'warranty_expiry' => $_POST['warranty_expiry'],
            'status'          => $_POST['status'],
            'remarks'         => $_POST['remarks']

        ]);

        header('Location: ?page=assets&success=1');
        exit;
    }
}

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

    <?php if (!empty($error)): ?>

        <div class="bg-red-100 border border-red-500 text-red-700 p-4 rounded mb-5">

            <h3 class="font-bold text-lg">

                ⚠ Asset Already Exists

            </h3>

            <div class="mt-2">

                <?= $error ?>

            </div>

        </div>

    <?php endif; ?>

    <div class="bg-white p-6 rounded shadow">

        <h1 class="text-3xl font-bold mb-6">

            Add Asset

        </h1>

        <form method="POST">

            <div class="mb-4">

                <label class="block mb-2">
                    Asset Tag
                </label>

                <input
                    type="text"
                    name="asset_tag"
                    required
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Serial Number
                </label>

                <input
                    type="text"
                    name="serial_number"
                    required
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Asset Name
                </label>

                <input
                    type="text"
                    name="asset_name"
                    required
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Category
                </label>

                <input
                    type="text"
                    name="category"
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Brand
                </label>

                <input
                    type="text"
                    name="brand"
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Model
                </label>

                <input
                    type="text"
                    name="model"
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Vendor
                </label>

                <select
                    name="vendor_id"
                    required
                    class="border p-2 rounded w-full">

                    <option value="">
                        Select Vendor
                    </option>

                    <?php foreach ($vendors as $vendor): ?>

                        <option value="<?= $vendor['id'] ?>">

                            <?= htmlspecialchars($vendor['company_name']) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Purchase Date
                </label>

                <input
                    type="date"
                    name="purchase_date"
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Warranty Expiry
                </label>

                <input
                    type="date"
                    name="warranty_expiry"
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Status
                </label>

                <select
                    name="status"
                    class="border p-2 rounded w-full">

                    <option value="Available">Available</option>
                    <option value="Assigned">Assigned</option>
                    <option value="Repair">Repair</option>
                    <option value="Disposed">Disposed</option>

                </select>

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Remarks
                </label>

                <textarea
                    name="remarks"
                    rows="4"
                    class="border p-2 rounded w-full"></textarea>

            </div>

            <button
                type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">

                Save Asset

            </button>

            <a
                href="?page=assets"
                class="ml-2 bg-gray-500 text-white px-5 py-2 rounded hover:bg-gray-600">

                Cancel

            </a>

        </form>

    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
