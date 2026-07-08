<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

$vendor = trim($_GET['vendor'] ?? '');
$from = trim($_GET['from'] ?? '');
$to = trim($_GET['to'] ?? '');

$sql = "

SELECT

a.*,

v.company_name

FROM assets a

LEFT JOIN vendors v
ON a.vendor_id=v.id

WHERE 1=1

";

$params=[];

if($vendor!="")
{

$sql.="

AND a.vendor_id=:vendor

";

$params['vendor']=$vendor;

}

if($from!="")
{

$sql.="

AND a.purchase_date>=:from

";

$params['from']=$from;

}

if($to!="")
{

$sql.="

AND a.purchase_date<=:to

";

$params['to']=$to;

}

$sql.="

ORDER BY purchase_date DESC

";

$stmt=$db->prepare($sql);

$stmt->execute($params);

$assets=$stmt->fetchAll(PDO::FETCH_ASSOC);

$vendors=$db->query("

SELECT id,company_name

FROM vendors

ORDER BY company_name

")->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

$totalPurchased=count($assets);

$totalWarranty=0;

foreach($assets as $asset)
{

if(
!empty($asset['warranty_expiry']) &&
$asset['warranty_expiry']>=date('Y-m-d')
)
{

$totalWarranty++;

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

Purchase Report

</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__."/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="p-8">

<div class="flex justify-between items-center mb-8">

<div>

<h1 class="text-4xl font-bold">

Purchase Report

</h1>

<p class="text-gray-500">

Asset Purchase History

</p>

</div>

<div class="flex gap-3">

<button
onclick="window.print()"
class="bg-blue-600 text-white px-6 py-3 rounded-lg">

🖨 Print

</button>

<a
href="?page=reports"
class="bg-gray-600 text-white px-6 py-3 rounded-lg">

← Back

</a>

</div>

</div>

<div class="grid grid-cols-2 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p>Total Purchased Assets</p>

<h2 class="text-4xl font-bold text-blue-700">

<?= $totalPurchased ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p>Assets Under Warranty</p>

<h2 class="text-4xl font-bold text-green-700">

<?= $totalWarranty ?>

</h2>

</div>
</div>

<!-- Filter -->

<div class="bg-white rounded-xl shadow mb-8">

<div class="p-6">

<form method="GET">

<input
type="hidden"
name="page"
value="report-purchase">

<div class="grid grid-cols-4 gap-4">

<div>

<label class="block mb-2 font-semibold">

Vendor

</label>

<select
name="vendor"
class="w-full border rounded-lg px-4 py-3">

<option value="">

All Vendors

</option>

<?php foreach($vendors as $v): ?>

<option
value="<?= $v['id']; ?>"
<?= $vendor==$v['id']?'selected':''; ?>>

<?= htmlspecialchars($v['company_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div>

<label class="block mb-2 font-semibold">

From Date

</label>

<input
type="date"
name="from"
value="<?= htmlspecialchars($from); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

To Date

</label>

<input
type="date"
name="to"
value="<?= htmlspecialchars($to); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<div class="flex items-end gap-3">

<button
class="bg-blue-600 text-white px-6 py-3 rounded-lg">

Search

</button>

<a
href="?page=report-purchase"
class="bg-gray-500 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</div>

</form>

</div>

</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white">

<tr>

<th class="px-4 py-4 text-left">

Asset ID

</th>

<th class="px-4 py-4 text-left">

Asset

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

</tr>

</thead>

<tbody>
<?php if(count($assets)>0): ?>

<?php foreach($assets as $asset): ?>

<tr class="border-b hover:bg-blue-50">

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

<?= htmlspecialchars($asset['warranty_expiry']); ?>

</td>

<td class="px-4 py-4 text-center">

<?php

$statusClass = match($asset['status'])
{
    'Available' => 'bg-green-100 text-green-700',
    'Assigned' => 'bg-blue-100 text-blue-700',
    'Repair' => 'bg-yellow-100 text-yellow-700',
    'Disposed' => 'bg-red-100 text-red-700',
    default => 'bg-gray-100 text-gray-700'
};

?>

<span class="<?= $statusClass ?> px-3 py-1 rounded-full text-xs font-bold">

<?= htmlspecialchars($asset['status']); ?>

</span>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center py-12 text-gray-500">

No purchase records found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<div class="flex justify-end gap-4 mt-8">

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

🖨 Print

</button>

<a
href="?page=report-export-excel&type=purchase"
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

📊 Export Excel

</a>

<a
href="?page=report-export-pdf&type=purchase"
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