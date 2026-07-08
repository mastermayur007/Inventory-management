<?php

use App\Controllers\AssetController;
use App\Controllers\VendorController;

$db = new Database();
$conn = $db->connect();

$assetController = new AssetController($conn);
$vendorController = new VendorController($conn);

$id = $_GET['id'] ?? 0;

$asset = $assetController->getById($id);

if (!$asset)
{
    die("Asset not found.");
}

$vendors = $vendorController->getDropdown();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $assetController->update(
        $id,
        [

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

        ]
    );

    header("Location:?page=assets&updated=1");
    exit;
}

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';

?>

<div class="ml-64 p-8 bg-gray-100 min-h-screen">

<div class="max-w-6xl mx-auto">

<div class="bg-white rounded-xl shadow-lg overflow-hidden">

<div class="bg-gradient-to-r from-blue-700 to-blue-500 px-8 py-6">

<h1 class="text-3xl font-bold text-white">

Edit Asset

</h1>

<p class="text-blue-100 mt-2">

Update Asset Information

</p>

</div>

<form method="POST">

<div class="p-8">

<div class="grid grid-cols-2 gap-6">

<!-- Asset ID -->

<div>

<label class="block text-sm font-semibold mb-2">

Asset ID

</label>

<input
type="text"
value="<?= htmlspecialchars($asset['asset_id']); ?>"
readonly
class="w-full border rounded-lg p-3 bg-gray-100 text-gray-600">

</div>

<!-- Asset Tag -->

<div>

<label class="block text-sm font-semibold mb-2">

Asset Tag

</label>

<input
type="text"
name="asset_tag"
required
value="<?= htmlspecialchars($asset['asset_tag']); ?>"
class="w-full border rounded-lg p-3">

</div>
<!-- Serial Number -->

<div>

<label class="block text-sm font-semibold mb-2">

Serial Number

</label>

<input
type="text"
name="serial_number"
required
value="<?= htmlspecialchars($asset['serial_number']); ?>"
class="w-full border rounded-lg p-3">

</div>

<!-- Asset Name -->

<div>

<label class="block text-sm font-semibold mb-2">

Asset Name

</label>

<input
type="text"
name="asset_name"
required
value="<?= htmlspecialchars($asset['asset_name']); ?>"
class="w-full border rounded-lg p-3">

</div>

<!-- Category -->

<div>

<label class="block text-sm font-semibold mb-2">

Category

</label>

<input
type="text"
name="category"
required
value="<?= htmlspecialchars($asset['category']); ?>"
class="w-full border rounded-lg p-3">

</div>

<!-- Brand -->

<div>

<label class="block text-sm font-semibold mb-2">

Brand

</label>

<input
type="text"
name="brand"
required
value="<?= htmlspecialchars($asset['brand']); ?>"
class="w-full border rounded-lg p-3">

</div>

<!-- Model -->

<div>

<label class="block text-sm font-semibold mb-2">

Model

</label>

<input
type="text"
name="model"
required
value="<?= htmlspecialchars($asset['model']); ?>"
class="w-full border rounded-lg p-3">

</div>

<!-- Vendor -->

<div>

<label class="block text-sm font-semibold mb-2">

Vendor

</label>

<select
name="vendor_id"
required
class="w-full border rounded-lg p-3">

<option value="">

Select Vendor

</option>

<?php foreach($vendors as $vendor): ?>

<option
value="<?= $vendor['id']; ?>"
<?= $vendor['id'] == $asset['vendor_id'] ? 'selected' : ''; ?>>

<?= htmlspecialchars($vendor['company_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<!-- Purchase Date -->

<div>

<label class="block text-sm font-semibold mb-2">

Purchase Date

</label>

<input
type="date"
name="purchase_date"
value="<?= $asset['purchase_date']; ?>"
class="w-full border rounded-lg p-3">

</div>

<!-- Warranty Expiry -->

<div>

<label class="block text-sm font-semibold mb-2">

Warranty Expiry

</label>

<input
type="date"
name="warranty_expiry"
value="<?= $asset['warranty_expiry']; ?>"
class="w-full border rounded-lg p-3">

</div>

<!-- Status -->

<div>

<label class="block text-sm font-semibold mb-2">

Status

</label>

<select
name="status"
class="w-full border rounded-lg p-3">

<option
value="Available"
<?= $asset['status']=="Available" ? "selected" : ""; ?>>

Available

</option>

<option
value="Assigned"
<?= $asset['status']=="Assigned" ? "selected" : ""; ?>>

Assigned

</option>

<option
value="Repair"
<?= $asset['status']=="Repair" ? "selected" : ""; ?>>

Repair

</option>

<option
value="Disposed"
<?= $asset['status']=="Disposed" ? "selected" : ""; ?>>

Disposed

</option>

<option
value="Retired"
<?= $asset['status']=="Retired" ? "selected" : ""; ?>>

Retired

</option>

</select>

</div>

</div>

<!-- End Grid -->
<!-- Remarks -->

<div class="mt-8">

    <label class="block text-sm font-semibold mb-2">

        Remarks

    </label>

    <textarea
        name="remarks"
        rows="5"
        class="w-full border rounded-lg p-3 resize-none"
        placeholder="Enter remarks about this asset..."><?= htmlspecialchars($asset['remarks']); ?></textarea>

</div>

<!-- Information Card -->

<div class="mt-8">

    <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded">

        <h3 class="font-bold text-blue-700 mb-2">

            Asset Information

        </h3>

        <ul class="list-disc ml-6 text-gray-700 space-y-1">

            <li><strong>Asset ID</strong> cannot be changed.</li>

            <li>Asset Tag should remain unique.</li>

            <li>Serial Number should match the physical device.</li>

            <li>Changing the Status affects Asset Assignment.</li>

        </ul>

    </div>

</div>

<!-- Buttons -->

<div class="flex justify-end gap-3 mt-8 border-t pt-6">

    <a
        href="?page=assets"
        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition">

        Cancel

    </a>

    <button
        type="reset"
        class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg transition">

        Reset

    </button>

    <button
        type="submit"
        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition shadow">

        Update Asset

    </button>

</div>

</div>

</form>

</div>

</div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?> 