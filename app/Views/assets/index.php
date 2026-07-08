<?php

use App\Controllers\AssetController;

$db = new Database();
$conn = $db->connect();

$assetController = new AssetController($conn);

$assets = $assetController->index();

/*
|--------------------------------------------------------------------------
| Search & Filter
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';

if ($search != '' || $status != '')
{
    $assets = array_filter($assets, function ($asset) use ($search, $status)
    {
        $matchSearch = true;
        $matchStatus = true;

        if ($search != '')
        {
            $keyword = strtolower($search);

            $matchSearch =
                strpos(strtolower($asset['asset_id']), $keyword) !== false ||
                strpos(strtolower($asset['asset_tag']), $keyword) !== false ||
                strpos(strtolower($asset['serial_number']), $keyword) !== false ||
                strpos(strtolower($asset['asset_name']), $keyword) !== false;
        }

        if ($status != '')
        {
            $matchStatus = $asset['status'] == $status;
        }

        return $matchSearch && $matchStatus;
    });
}

$totalAssets = count($assets);

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';

?>

<div class="ml-64 bg-gray-100 min-h-screen">

<div class="p-8">

<?php if(isset($_GET['success'])): ?>

<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-5 py-4 rounded-lg">

<strong>

✅ Asset saved successfully.

</strong>

</div>

<?php endif; ?>

<div class="bg-white rounded-xl shadow-lg">

<div class="flex items-center justify-between border-b px-8 py-6">

<div>

<h1 class="text-3xl font-bold text-gray-800">

Asset Management

</h1>

<p class="text-gray-500 mt-1">

Manage all company IT assets

</p>

</div>

<a
href="?page=asset-create"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow">

+ Add Asset

</a>

</div>

<!-- Dashboard Cards -->

<div class="grid grid-cols-4 gap-6 p-6">

<div class="bg-blue-50 rounded-xl p-5">

<p class="text-gray-500">

Total Assets

</p>

<h2 class="text-3xl font-bold text-blue-700">

<?= $totalAssets; ?>

</h2>

</div>

<div class="bg-green-50 rounded-xl p-5">

<p class="text-gray-500">

Available

</p>

<h2 class="text-3xl font-bold text-green-700">

<?= count(array_filter($assets, fn($a)=>$a['status']=='Available')); ?>

</h2>

</div>

<div class="bg-red-50 rounded-xl p-5">

<p class="text-gray-500">

Assigned

</p>

<h2 class="text-3xl font-bold text-red-700">

<?= count(array_filter($assets, fn($a)=>$a['status']=='Assigned')); ?>

</h2>

</div>

<div class="bg-yellow-50 rounded-xl p-5">

<p class="text-gray-500">

Repair

</p>

<h2 class="text-3xl font-bold text-yellow-700">

<?= count(array_filter($assets, fn($a)=>$a['status']=='Repair')); ?>

</h2>

</div>

</div>

<!-- Search -->

<div class="px-6 pb-6">

<form method="GET">

<input
type="hidden"
name="page"
value="assets">

<div class="flex gap-4">

<input
type="text"
name="search"
value="<?= htmlspecialchars($search); ?>"
placeholder="Search Asset ID, Asset Tag, Serial Number..."
class="flex-1 border rounded-lg px-4 py-3">

<select
name="status"
class="border rounded-lg px-4 py-3">

<option value="">

All Status

</option>

<option value="Available" <?= $status=='Available'?'selected':''; ?>>

Available

</option>

<option value="Assigned" <?= $status=='Assigned'?'selected':''; ?>>

Assigned

</option>

<option value="Repair" <?= $status=='Repair'?'selected':''; ?>>

Repair

</option>

<option value="Disposed" <?= $status=='Disposed'?'selected':''; ?>>

Disposed

</option>

<option value="Retired" <?= $status=='Retired'?'selected':''; ?>>

Retired

</option>

</select>

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

Search

</button>

<a
href="?page=assets"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</form>

</div>

<div class="overflow-x-auto">

<table class="min-w-full border-collapse">
    <thead>

<tr class="bg-gray-800 text-white text-sm uppercase">

<th class="px-4 py-4 text-left">

Asset ID

</th>

<th class="px-4 py-4 text-left">

Asset Tag

</th>

<th class="px-4 py-4 text-left">

Asset Name

</th>

<th class="px-4 py-4 text-left">

Category

</th>

<th class="px-4 py-4 text-left">

Brand

</th>

<th class="px-4 py-4 text-left">

Model

</th>

<th class="px-4 py-4 text-left">

Vendor

</th>

<th class="px-4 py-4 text-left">

Purchase Date

</th>

<th class="px-4 py-4 text-left">

Warranty

</th>

<th class="px-4 py-4 text-center">

Status

</th>

<th class="px-4 py-4 text-center">

Actions

</th>

</tr>

</thead>

<tbody>

<?php if(count($assets)>0): ?>

<?php foreach($assets as $asset): ?>

<tr class="border-b hover:bg-blue-50 transition">

<td class="px-4 py-4 font-bold text-blue-700">

<?= htmlspecialchars($asset['asset_id']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['asset_tag']); ?>

</td>

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($asset['asset_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($asset['serial_number']); ?>

</div>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['category']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['brand']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['model']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['company_name'] ?? 'N/A'); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['purchase_date']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['warranty_expiry']); ?>

</td>

<td class="px-4 py-4 text-center">

<?php

switch($asset['status'])
{

case 'Available':

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Available</span>';

break;

case 'Assigned':

echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Assigned</span>';

break;

case 'Repair':

echo '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Repair</span>';

break;

case 'Disposed':

echo '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Disposed</span>';

break;

case 'Retired':

echo '<span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">Retired</span>';

break;

default:

echo '<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">'
. htmlspecialchars($asset['status']) .
'</span>';

break;

}

?>

</td>

<td class="px-4 py-4">

<div class="flex justify-center gap-2">
    <!-- View -->

<a
href="?page=asset-show&id=<?= $asset['id']; ?>"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm transition">

View

</a>

<!-- Edit -->

<a
href="?page=asset-edit&id=<?= $asset['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm transition">

Edit

</a>

<!-- Delete -->

<a
href="?page=asset-delete&id=<?= $asset['id']; ?>"
onclick="return confirm('Are you sure you want to delete this asset?');"
class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm transition">

Delete

</a>

</div>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td
colspan="11"
class="text-center py-12">

<div class="flex flex-col items-center">

<div class="text-6xl mb-4">

📦

</div>

<h2 class="text-2xl font-bold text-gray-600">

No Assets Found

</h2>

<p class="text-gray-500 mt-2">

Try changing your search or add a new asset.

</p>

<a
href="?page=asset-create"
class="mt-5 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

+ Add New Asset

</a>

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const search = document.querySelector("input[name='search']");

    if(search){

        search.focus();

    }

});

</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>