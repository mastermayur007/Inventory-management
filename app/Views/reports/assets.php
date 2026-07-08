<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

/*
|--------------------------------------------------------------------------
| Search & Filter
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');
$category = trim($_GET['category'] ?? '');

$sql = "
SELECT
    a.*,
    v.company_name
FROM assets a
LEFT JOIN vendors v
    ON a.vendor_id = v.id
WHERE 1=1
";

$params = [];

if ($search != '') {

    $sql .= "
    AND (
        a.asset_id LIKE :search
        OR a.asset_tag LIKE :search
        OR a.serial_number LIKE :search
        OR a.asset_name LIKE :search
    )
    ";

    $params['search'] = "%{$search}%";
}

if ($status != '') {

    $sql .= "
    AND a.status = :status
    ";

    $params['status'] = $status;
}

if ($category != '') {

    $sql .= "
    AND a.category = :category
    ";

    $params['category'] = $category;
}

$sql .= "
ORDER BY a.asset_id ASC
";

$stmt = $db->prepare($sql);
$stmt->execute($params);

$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Dashboard Counts
|--------------------------------------------------------------------------
*/

$total = count($assets);

$available = 0;
$assigned = 0;
$repair = 0;
$disposed = 0;

foreach($assets as $asset)
{
    switch($asset['status'])
    {
        case 'Available':
            $available++;
            break;

        case 'Assigned':
            $assigned++;
            break;

        case 'Repair':
            $repair++;
            break;

        case 'Disposed':
            $disposed++;
            break;
    }
}

$categories = $db->query("
SELECT DISTINCT category
FROM assets
ORDER BY category
")->fetchAll(PDO::FETCH_COLUMN);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Asset Report

</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="p-8">

<div class="flex justify-between items-center mb-8">

<div>

<h1 class="text-4xl font-bold">

Asset Report

</h1>

<p class="text-gray-500">

Complete Inventory Report

</p>

</div>

<div class="flex gap-3">

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">

🖨 Print

</button>

<a
href="?page=reports"
class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-lg">

← Back

</a>

</div>

</div>
<!-- Dashboard Cards -->

<div class="grid grid-cols-4 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Total Assets

</p>

<h2 class="text-4xl font-bold text-blue-700 mt-3">

<?= $total ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Available

</p>

<h2 class="text-4xl font-bold text-green-600 mt-3">

<?= $available ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Assigned

</p>

<h2 class="text-4xl font-bold text-red-600 mt-3">

<?= $assigned ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Repair

</p>

<h2 class="text-4xl font-bold text-yellow-600 mt-3">

<?= $repair ?>

</h2>

</div>

</div>

<!-- Search & Filter -->

<div class="bg-white rounded-xl shadow mb-8">

<div class="p-6">

<form method="GET">

<input
type="hidden"
name="page"
value="report-assets">

<div class="grid grid-cols-4 gap-4">

<!-- Search -->

<div>

<label class="block text-sm font-semibold mb-2">

Search

</label>

<input
type="text"
name="search"
value="<?= htmlspecialchars($search) ?>"
placeholder="Asset ID / Tag / Serial"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- Status -->

<div>

<label class="block text-sm font-semibold mb-2">

Status

</label>

<select
name="status"
class="w-full border rounded-lg px-4 py-3">

<option value="">All Status</option>

<option value="Available" <?= $status=="Available"?"selected":""; ?>>

Available

</option>

<option value="Assigned" <?= $status=="Assigned"?"selected":""; ?>>

Assigned

</option>

<option value="Repair" <?= $status=="Repair"?"selected":""; ?>>

Repair

</option>

<option value="Disposed" <?= $status=="Disposed"?"selected":""; ?>>

Disposed

</option>

</select>

</div>

<!-- Category -->

<div>

<label class="block text-sm font-semibold mb-2">

Category

</label>

<select
name="category"
class="w-full border rounded-lg px-4 py-3">

<option value="">

All Categories

</option>

<?php foreach($categories as $cat): ?>

<option
value="<?= htmlspecialchars($cat) ?>"
<?= $category==$cat?'selected':''; ?>>

<?= htmlspecialchars($cat) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<!-- Buttons -->

<div class="flex items-end gap-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Search

</button>

<a
href="?page=report-assets"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</div>

</form>

</div>

</div>

<!-- Asset Report Table -->

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white uppercase text-sm">

<tr>

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

Vendor

</th>

<th class="px-4 py-4 text-left">

Purchase

</th>

<th class="px-4 py-4 text-left">

Warranty

</th>

<th class="px-4 py-4 text-center">

Status

</th>

</tr>

</thead>

<tbody>
    <?php if(count($assets) > 0): ?>

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

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">

Available

</span>';

break;

case 'Assigned':

echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">

Assigned

</span>';

break;

case 'Repair':

echo '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">

Repair

</span>';

break;

case 'Disposed':

echo '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">

Disposed

</span>';

break;

case 'Retired':

echo '<span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">

Retired

</span>';

break;

default:

echo '<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">' .
htmlspecialchars($asset['status']) .
'</span>';

break;

}

?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="9" class="text-center py-16">

<div class="flex flex-col items-center">

<div class="text-6xl mb-4">

📦

</div>

<h2 class="text-2xl font-bold text-gray-600">

No Assets Found

</h2>

<p class="text-gray-500 mt-2">

Try changing your search filters.

</p>

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<!-- Export Buttons -->

<div class="flex justify-end gap-4 mt-8">

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

🖨 Print

</button>

<a
href="?page=report-export-excel"
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

📊 Export Excel

</a>

<a
href="?page=report-export-pdf"
class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg">

📄 Export PDF

</a>

</div>

<div class="mt-10 text-center text-gray-500 text-sm">

Generated on <?= date('d M Y H:i'); ?>

</div>

</div>

</div>

</div>

</body>

</html>