
<?php

use App\Controllers\AssetController;

$db = new Database();
$conn = $db->connect();

$assetController = new AssetController($conn);

$id = $_GET['id'];

$asset = $assetController->getById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $assetController->update(
        $id,
        [
            'asset_tag' => $_POST['asset_tag'],
            'serial_number' => $_POST['serial_number'],
            'asset_name' => $_POST['asset_name'],
            'category' => $_POST['category'],
            'brand' => $_POST['brand'],
            'model' => $_POST['model'],
            'vendor_id' => $_POST['vendor_id'],
            'purchase_date' => $_POST['purchase_date'],
            'warranty_expiry' => $_POST['warranty_expiry'],
            'status' => $_POST['status'],
            'remarks' => $_POST['remarks']
        ]
    );

    header('Location: ?page=assets');
    exit;
}

require __DIR__.'/../layouts/header.php';
require __DIR__.'/../layouts/sidebar.php';
require __DIR__.'/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

<div class="bg-white p-6 rounded shadow">

<h1 class="text-3xl font-bold mb-6">

Edit Asset

</h1>

<form method="POST">

<div class="mb-4">
<label>Asset Tag</label>

<input
type="text"
name="asset_tag"
value="<?= htmlspecialchars($asset['asset_tag']) ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Serial Number</label>

<input
type="text"
name="serial_number"
value="<?= htmlspecialchars($asset['serial_number']) ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Asset Name</label>

<input
type="text"
name="asset_name"
value="<?= htmlspecialchars($asset['asset_name']) ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Category</label>

<input
type="text"
name="category"
value="<?= htmlspecialchars($asset['category']) ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Brand</label>

<input
type="text"
name="brand"
value="<?= htmlspecialchars($asset['brand']) ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Model</label>

<input
type="text"
name="model"
value="<?= htmlspecialchars($asset['model']) ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Vendor ID</label>

<input
type="number"
name="vendor_id"
value="<?= htmlspecialchars($asset['vendor_id']) ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Purchase Date</label>

<input
type="date"
name="purchase_date"
value="<?= $asset['purchase_date'] ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Warranty Expiry</label>

<input
type="date"
name="warranty_expiry"
value="<?= $asset['warranty_expiry'] ?>"
class="border p-2 rounded w-full">
</div>

<div class="mb-4">
<label>Status</label>

<select
name="status"
class="border p-2 rounded w-full">

<option value="Available" <?= $asset['status']=='Available'?'selected':'' ?>>
Available
</option>

<option value="Assigned" <?= $asset['status']=='Assigned'?'selected':'' ?>>
Assigned
</option>

<option value="Repair" <?= $asset['status']=='Repair'?'selected':'' ?>>
Repair
</option>

<option value="Retired" <?= $asset['status']=='Retired'?'selected':'' ?>>
Retired
</option>

</select>

</div>

<div class="mb-4">
<label>Remarks</label>

<textarea
name="remarks"
class="border p-2 rounded w-full"><?= htmlspecialchars($asset['remarks']) ?></textarea>

</div>

<button
class="bg-green-600 text-white px-5 py-2 rounded">

Update Asset

</button>

</form>

</div>

</div>

<?php require __DIR__.'/../layouts/footer.php'; ?>
