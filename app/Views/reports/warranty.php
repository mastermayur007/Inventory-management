<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$today = date('Y-m-d');
$next30 = date('Y-m-d', strtotime('+30 days'));

$search = trim($_GET['search'] ?? '');

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

if($search != "")
{
    $sql .= "
    AND
    (
        a.asset_id LIKE :search
        OR a.asset_tag LIKE :search
        OR a.asset_name LIKE :search
        OR a.serial_number LIKE :search
    )
    ";

    $params['search'] = "%{$search}%";
}

$sql .= "
ORDER BY warranty_expiry ASC
";

$stmt = $db->prepare($sql);
$stmt->execute($params);

$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Dashboard Counts
|--------------------------------------------------------------------------
*/

$expired = 0;
$expiring = 0;
$active = 0;

foreach($assets as $asset)
{

    if(empty($asset['warranty_expiry']))
    {
        continue;
    }

    if($asset['warranty_expiry'] < $today)
    {
        $expired++;
    }
    elseif($asset['warranty_expiry'] <= $next30)
    {
        $expiring++;
    }
    else
    {
        $active++;
    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Warranty Report

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

Warranty Report

</h1>

<p class="text-gray-500">

Monitor Asset Warranty Status

</p>

</div>

<div class="flex gap-3">

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

🖨 Print

</button>

<a
href="?page=reports"
class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">

← Back

</a>

</div>

</div>

<!-- Dashboard Cards -->

<div class="grid grid-cols-3 gap-6 mb-8">

<div class="bg-red-50 rounded-xl shadow p-6">

<p class="text-gray-600">

Expired Warranty

</p>

<h2 class="text-4xl font-bold text-red-600 mt-3">

<?= $expired ?>

</h2>

</div>

<div class="bg-yellow-50 rounded-xl shadow p-6">

<p class="text-gray-600">

Expiring in 30 Days

</p>

<h2 class="text-4xl font-bold text-yellow-600 mt-3">

<?= $expiring ?>

</h2>

</div>

<div class="bg-green-50 rounded-xl shadow p-6">

<p class="text-gray-600">

Under Warranty

</p>

<h2 class="text-4xl font-bold text-green-600 mt-3">

<?= $active ?>

</h2>

</div>

</div>

<!-- Search -->

<div class="bg-white rounded-xl shadow mb-8">

<div class="p-6">

<form method="GET">

<input
type="hidden"
name="page"
value="report-warranty">

<div class="flex gap-4">

<input
type="text"
name="search"
value="<?= htmlspecialchars($search) ?>"
placeholder="Search Asset ID, Asset Tag, Asset Name..."
class="flex-1 border rounded-lg px-4 py-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

Search

</button>

<a
href="?page=report-warranty"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</form>

</div>

</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white">

<tr>

<th class="px-4 py-4 text-left">Asset ID</th>

<th class="px-4 py-4 text-left">Asset</th>

<th class="px-4 py-4 text-left">Vendor</th>

<th class="px-4 py-4 text-left">Purchase Date</th>

<th class="px-4 py-4 text-left">Warranty Expiry</th>

<th class="px-4 py-4 text-center">Warranty Status</th>

</tr>

</thead>

<tbody>
    <?php if(count($assets) > 0): ?>

<?php foreach($assets as $asset): ?>

<?php

if(empty($asset['warranty_expiry']))
{
    $status = "No Warranty";
    $badge = "bg-gray-100 text-gray-700";
}
elseif($asset['warranty_expiry'] < $today)
{
    $status = "Expired";
    $badge = "bg-red-100 text-red-700";
}
elseif($asset['warranty_expiry'] <= $next30)
{
    $status = "Expiring Soon";
    $badge = "bg-yellow-100 text-yellow-700";
}
else
{
    $status = "Under Warranty";
    $badge = "bg-green-100 text-green-700";
}

?>

<tr class="border-b hover:bg-blue-50 transition">

<td class="px-4 py-4 font-bold text-blue-700">

<?= htmlspecialchars($asset['asset_id']); ?>

</td>

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($asset['asset_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($asset['asset_tag']); ?>

</div>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['company_name'] ?? 'N/A'); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['purchase_date']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($asset['warranty_expiry'] ?: 'N/A'); ?>

</td>

<td class="px-4 py-4 text-center">

<span class="<?= $badge ?> px-3 py-1 rounded-full text-xs font-bold">

<?= $status ?>

</span>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center py-16">

<div class="flex flex-col items-center">

<div class="text-6xl mb-4">

🛡️

</div>

<h2 class="text-2xl font-bold text-gray-600">

No Assets Found

</h2>

<p class="text-gray-500 mt-2">

No warranty records match your search.

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
href="?page=report-export-excel&type=warranty"
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

📊 Export Excel

</a>

<a
href="?page=report-export-pdf&type=warranty"
class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg">

📄 Export PDF

</a>

</div>

<!-- Legend -->

<div class="bg-white rounded-xl shadow mt-8 p-6">

<h3 class="text-xl font-bold mb-4">

Warranty Status Guide

</h3>

<div class="flex flex-wrap gap-4">

<span class="bg-green-100 text-green-700 px-3 py-2 rounded-full">

🟢 Under Warranty

</span>

<span class="bg-yellow-100 text-yellow-700 px-3 py-2 rounded-full">

🟡 Expiring Soon (30 Days)

</span>

<span class="bg-red-100 text-red-700 px-3 py-2 rounded-full">

🔴 Expired

</span>

<span class="bg-gray-100 text-gray-700 px-3 py-2 rounded-full">

⚪ No Warranty

</span>

</div>

</div>

<div class="mt-10 text-center text-gray-500 text-sm">

Generated on <?= date('d M Y H:i'); ?>

</div>

</div>

</div>

</div>

</body>

</html>