<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$search = trim($_GET['search'] ?? '');

$sql = "

SELECT

v.id,
v.company_name,
v.contact_person,
v.email,
v.phone,

COUNT(a.id) total_assets,

SUM(CASE WHEN a.status='Available' THEN 1 ELSE 0 END) available_assets,

SUM(CASE WHEN a.status='Assigned' THEN 1 ELSE 0 END) assigned_assets,

SUM(CASE WHEN a.status='Repair' THEN 1 ELSE 0 END) repair_assets,

SUM(CASE WHEN a.status='Disposed' THEN 1 ELSE 0 END) disposed_assets

FROM vendors v

LEFT JOIN assets a

ON a.vendor_id=v.id

WHERE 1=1

";

$params=[];

if($search!="")
{

$sql.="

AND(

v.company_name LIKE :search

OR v.contact_person LIKE :search

OR v.email LIKE :search

)

";

$params['search']="%{$search}%";

}

$sql.="

GROUP BY v.id

ORDER BY v.company_name

";

$stmt=$db->prepare($sql);

$stmt->execute($params);

$vendors=$stmt->fetchAll(PDO::FETCH_ASSOC);

$totalVendors=count($vendors);

$totalAssets=0;

foreach($vendors as $vendor)
{

$totalAssets+=$vendor['total_assets'];

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

Vendor Report

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

Vendor Report

</h1>

<p class="text-gray-500">

Assets supplied by each vendor

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

<!-- Dashboard -->

<div class="grid grid-cols-2 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p>Total Vendors</p>

<h2 class="text-4xl font-bold text-blue-700">

<?= $totalVendors ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p>Total Assets</p>

<h2 class="text-4xl font-bold text-green-700">

<?= $totalAssets ?>

</h2>

</div>

</div>
<!-- Search Panel -->

<div class="bg-white rounded-xl shadow mb-8">

    <div class="p-6">

        <form method="GET">

            <input
                type="hidden"
                name="page"
                value="report-vendors">

            <div class="flex gap-4">

                <input
                    type="text"
                    name="search"
                    value="<?= htmlspecialchars($search); ?>"
                    placeholder="Search Vendor Name, Contact Person, Email..."
                    class="flex-1 border rounded-lg px-4 py-3">

                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                    Search

                </button>

                <a
                    href="?page=report-vendors"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

                    Reset

                </a>

            </div>

        </form>

    </div>

</div>

<!-- Vendor Table -->

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white">

<tr>

<th class="px-4 py-4 text-left">

Vendor

</th>

<th class="px-4 py-4 text-left">

Contact

</th>

<th class="px-4 py-4 text-center">

Assets

</th>

<th class="px-4 py-4 text-center">

Available

</th>

<th class="px-4 py-4 text-center">

Assigned

</th>

<th class="px-4 py-4 text-center">

Repair

</th>

<th class="px-4 py-4 text-center">

Disposed

</th>

</tr>

</thead>

<tbody>

<?php if(count($vendors)>0): ?>

<?php foreach($vendors as $vendor): ?>

<tr class="border-b hover:bg-blue-50">

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($vendor['company_name']); ?>

</div>

<div class="text-sm text-gray-500">

Vendor ID : <?= $vendor['id']; ?>

</div>

</td>

<td class="px-4 py-4">

<div>

<?= htmlspecialchars($vendor['contact_person']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($vendor['email']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($vendor['phone']); ?>

</div>

</td>

<td class="px-4 py-4 text-center font-bold">

<?= $vendor['total_assets']; ?>

</td>

<td class="px-4 py-4 text-center">

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

<?= $vendor['available_assets']; ?>

</span>

</td>

<td class="px-4 py-4 text-center">

<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">

<?= $vendor['assigned_assets']; ?>

</span>

</td>

<td class="px-4 py-4 text-center">

<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">

<?= $vendor['repair_assets']; ?>

</span>

</td>

<td class="px-4 py-4 text-center">

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

<?= $vendor['disposed_assets']; ?>

</span>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="7" class="text-center py-16">

<div class="text-6xl mb-4">

🏢

</div>

<h2 class="text-2xl font-bold text-gray-600">

No Vendors Found

</h2>

<p class="text-gray-500">

No vendor matches your search.

</p>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<!-- Export -->

<div class="flex justify-end gap-4 mt-8">

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

🖨 Print

</button>

<a
href="?page=report-export-excel&type=vendors"
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

📊 Export Excel

</a>

<a
href="?page=report-export-pdf&type=vendors"
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