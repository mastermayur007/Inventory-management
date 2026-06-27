<?php

require_once __DIR__ . "/../../Controllers/AssetAssignmentController.php";
require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$sql = "
SELECT
    aa.*,

    a.asset_tag,
    a.asset_name,
    a.brand,
    a.model,

    e.employee_code,
    CONCAT(e.first_name,' ',e.last_name) AS employee_name

FROM asset_assignments aa

INNER JOIN assets a
ON aa.asset_id=a.id

INNER JOIN employees e
ON aa.employee_id=e.id

ORDER BY aa.id DESC
";

$assignments=$db
    ->query($sql)
    ->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Asset Assignments</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

<?php require_once "../layouts/sidebar.php"; ?>

<div class="flex-1 p-8">

<div class="bg-white rounded-lg shadow">

<div class="flex justify-between items-center p-6 border-b">

<div>

<h1 class="text-2xl font-bold">

Asset Assignments

</h1>

<p class="text-gray-500">

Manage Assigned Company Assets

</p>

</div>

<a
href="?page=asset-assignment-create"
class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700">

Assign Asset

</a>

</div>

<div class="p-6">

<?php if(isset($_SESSION['success'])): ?>

<div
class="mb-4 p-4 rounded bg-green-100 text-green-700">

<?= $_SESSION['success']; ?>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>

<div
class="mb-4 p-4 rounded bg-red-100 text-red-700">

<?= $_SESSION['error']; ?>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>

<div class="overflow-x-auto">

<table
class="min-w-full border border-gray-300">

<thead class="bg-gray-200">

<tr>

<th class="px-4 py-3 border">#</th>

<th class="px-4 py-3 border">

Employee

</th>

<th class="px-4 py-3 border">

Asset

</th>

<th class="px-4 py-3 border">

Asset Tag

</th>

<th class="px-4 py-3 border">

Assigned Date

</th>

<th class="px-4 py-3 border">

Expected Return

</th>

<th class="px-4 py-3 border">

Status

</th>

<th class="px-4 py-3 border">

Actions

</th>

</tr>

</thead>

<tbody>

<?php if(empty($assignments)): ?>

<tr>

<td
colspan="8"
class="text-center p-5 text-gray-500">

No Asset Assignments Found

</td>

</tr>

<?php endif; ?>

<?php

$i=1;

foreach($assignments as $row):

?>

<tr
class="hover:bg-gray-50">

<td class="border px-4 py-3">

<?= $i++; ?>

</td>

<td class="border px-4 py-3">

<strong>

<?= $row['employee_name']; ?>

</strong>

<br>

<span class="text-gray-500">

<?= $row['employee_code']; ?>

</span>

</td>

<td class="border px-4 py-3">

<?= $row['asset_name']; ?>

<br>

<span class="text-gray-500">

<?= $row['brand']; ?>

<?= $row['model']; ?>

</span>

</td>

<td class="border px-4 py-3">

<?= $row['asset_tag']; ?>

</td>

<td class="border px-4 py-3">

<?= $row['assigned_date']; ?>

</td>

<td class="border px-4 py-3">

<?= $row['expected_return_date']; ?>

</td>

<td class="border px-4 py-3">

<?php

if($row['status']=="Assigned")
{

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Assigned</span>';

}
else
{

echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">Returned</span>';

}

?>

</td>

<td class="border px-4 py-3">

<div class="flex gap-2">

<a
href="?page=asset-assignment-show&id=<?= $row['id']; ?>"
class="bg-indigo-600 text-white px-3 py-2 rounded">

View

</a>

<a
href="?page=asset-assignment-edit&id=<?= $row['id']; ?>"
class="bg-yellow-500 text-white px-3 py-2 rounded">

Edit

</a>

<a
href="?page=asset-assignment-return&id=<?= $row['id']; ?>"
class="bg-green-600 text-white px-3 py-2 rounded">

Return

</a>

<a
href="?page=asset-assignment-delete&id=<?= $row['id']; ?>"
onclick="return confirm('Delete this assignment?')"
class="bg-red-600 text-white px-3 py-2 rounded">

Delete

</a>

</div>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</body>

</html>