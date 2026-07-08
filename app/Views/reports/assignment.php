<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');

$sql = "

SELECT

aa.*,

a.asset_id,
a.asset_tag,
a.asset_name,

e.employee_code,
CONCAT(e.first_name,' ',e.last_name) employee_name

FROM asset_assignments aa

INNER JOIN assets a
ON aa.asset_id=a.id

INNER JOIN employees e
ON aa.employee_id=e.id

WHERE 1=1

";

$params=[];

if($search!="")
{

$sql.="

AND(

a.asset_id LIKE :search

OR a.asset_tag LIKE :search

OR a.asset_name LIKE :search

OR e.employee_code LIKE :search

OR CONCAT(e.first_name,' ',e.last_name) LIKE :search

)

";

$params['search']="%{$search}%";

}

if($status!="")
{

$sql.="

AND aa.status=:status

";

$params['status']=$status;

}

$sql.="

ORDER BY aa.id DESC

";

$stmt=$db->prepare($sql);

$stmt->execute($params);

$records=$stmt->fetchAll(PDO::FETCH_ASSOC);

$total=count($records);

$assigned=0;
$returned=0;
$overdue=0;

foreach($records as $row)
{

if($row['status']=="Assigned")
{

$assigned++;

if(
!empty($row['expected_return_date']) &&
$row['expected_return_date']<date('Y-m-d')
)
{

$overdue++;

}

}
else
{

$returned++;

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

Assignment Report

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

Assignment Report

</h1>

<p class="text-gray-500">

Asset Assignment Summary

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

<div class="grid grid-cols-4 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p>Total Assignments</p>

<h2 class="text-4xl font-bold text-blue-700">

<?= $total ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p>Assigned</p>

<h2 class="text-4xl font-bold text-green-700">

<?= $assigned ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p>Returned</p>

<h2 class="text-4xl font-bold text-indigo-700">

<?= $returned ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p>Overdue</p>

<h2 class="text-4xl font-bold text-red-700">

<?= $overdue ?>

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
value="report-assignments">

<div class="grid grid-cols-3 gap-4">

<div>

<label class="block text-sm font-semibold mb-2">

Search

</label>

<input
type="text"
name="search"
value="<?= htmlspecialchars($search) ?>"
placeholder="Employee / Asset ID / Asset Tag..."
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block text-sm font-semibold mb-2">

Status

</label>

<select
name="status"
class="w-full border rounded-lg px-4 py-3">

<option value="">All Status</option>

<option
value="Assigned"
<?= $status=="Assigned"?"selected":""; ?>>

Assigned

</option>

<option
value="Returned"
<?= $status=="Returned"?"selected":""; ?>>

Returned

</option>

</select>

</div>

<div class="flex items-end gap-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Search

</button>

<a
href="?page=report-assignments"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</div>

</form>

</div>

</div>

<!-- Report Table -->

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white uppercase text-sm">

<tr>

<th class="px-4 py-4 text-left">

Employee

</th>

<th class="px-4 py-4 text-left">

Asset ID

</th>

<th class="px-4 py-4 text-left">

Asset

</th>

<th class="px-4 py-4 text-left">

Assigned Date

</th>

<th class="px-4 py-4 text-left">

Expected Return

</th>

<th class="px-4 py-4 text-center">

Status

</th>

</tr>

</thead>

<tbody>

<?php if(count($records)>0): ?>

<?php foreach($records as $row): ?>

<tr class="border-b hover:bg-blue-50 transition">

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['employee_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['employee_code']); ?>

</div>

</td>

<td class="px-4 py-4 font-bold text-blue-700">

<?= htmlspecialchars($row['asset_id']); ?>

</td>

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['asset_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['asset_tag']); ?>

</div>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($row['assigned_date']); ?>

</td>

<td class="px-4 py-4">

<?php

if(
!empty($row['expected_return_date'])
)
{

echo htmlspecialchars($row['expected_return_date']);

}
else
{

echo "-";

}

?>

</td>

<td class="px-4 py-4 text-center">

<?php

if($row['status']=="Assigned")
{

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">

Assigned

</span>';

}
else
{

echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">

Returned

</span>';

}

?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center py-16">

<div class="flex flex-col items-center">

<div class="text-6xl mb-4">

📋

</div>

<h2 class="text-2xl font-bold text-gray-600">

No Assignment Records Found

</h2>

<p class="text-gray-500 mt-2">

No records match your search.

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
href="?page=report-export-excel&type=assignment"
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

📊 Export Excel

</a>

<a
href="?page=report-export-pdf&type=assignment"
class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg">

📄 Export PDF

</a>

</div>

<!-- Report Summary -->

<div class="bg-white rounded-xl shadow mt-8">

<div class="border-b px-6 py-4">

<h2 class="text-2xl font-bold">

Assignment Summary

</h2>

</div>

<div class="grid grid-cols-4 gap-6 p-6">

<div>

<p class="text-gray-500">

Total Assignments

</p>

<h3 class="text-3xl font-bold text-blue-700">

<?= $total ?>

</h3>

</div>

<div>

<p class="text-gray-500">

Currently Assigned

</p>

<h3 class="text-3xl font-bold text-green-700">

<?= $assigned ?>

</h3>

</div>

<div>

<p class="text-gray-500">

Returned

</p>

<h3 class="text-3xl font-bold text-indigo-700">

<?= $returned ?>

</h3>

</div>

<div>

<p class="text-gray-500">

Overdue

</p>

<h3 class="text-3xl font-bold text-red-700">

<?= $overdue ?>

</h3>

</div>

</div>

</div>

<!-- Assignment Status Guide -->

<div class="bg-white rounded-xl shadow mt-8 p-6">

<h2 class="text-xl font-bold mb-5">

Assignment Status Guide

</h2>

<div class="flex flex-wrap gap-4">

<span class="bg-green-100 text-green-700 px-4 py-2 rounded-full">

🟢 Assigned

</span>

<span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full">

🔵 Returned

</span>

<span class="bg-red-100 text-red-700 px-4 py-2 rounded-full">

🔴 Overdue Return

</span>

</div>

</div>

<!-- Footer -->

<div class="mt-10 flex justify-between items-center text-sm text-gray-500 border-t pt-6">

<div>

Generated on:

<strong>

<?= date('d M Y H:i'); ?>

</strong>

</div>

<div>

IT Asset Management System

</div>

</div>

</div>

</div>

</div>

</body>

</html>