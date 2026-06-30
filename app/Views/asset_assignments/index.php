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
ON aa.asset_id = a.id

INNER JOIN employees e
ON aa.employee_id = e.id

ORDER BY aa.id DESC
";

$assignments = $db
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

<title>

Asset Assignments

</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="max-w-7xl mx-auto px-8 py-8">

<div class="bg-white rounded-xl shadow-lg overflow-hidden">

<!-- Header -->

<div class="flex items-center justify-between px-8 py-6 border-b bg-gray-50">

<div>

<h1 class="text-3xl font-bold text-gray-800">

Asset Assignments

</h1>

<p class="text-gray-500 mt-1">

Manage Company Asset Assignments

</p>

</div>

<a
href="?page=asset-assignment-create"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow font-medium">

+ Assign Asset

</a>

</div>

<!-- Alerts -->

<div class="p-6">

<?php if(isset($_SESSION['success'])): ?>

<div
class="mb-5 rounded-lg bg-green-100 border border-green-300 text-green-700 px-5 py-4">

<?= $_SESSION['success']; ?>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>

<div
class="mb-5 rounded-lg bg-red-100 border border-red-300 text-red-700 px-5 py-4">

<?= $_SESSION['error']; ?>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>

<!-- Table -->

<div class="overflow-x-auto">

<table
class="min-w-full border border-gray-200">

<thead class="bg-gray-100">

<tr>

<th class="px-6 py-4 border text-left">

#

</th>

<th class="px-6 py-4 border text-left">

Employee

</th>

<th class="px-6 py-4 border text-left">

Asset

</th>

<th class="px-6 py-4 border text-left">

Asset Tag

</th>

<th class="px-6 py-4 border">

Assigned Date

</th>

<th class="px-6 py-4 border">

Expected Return

</th>

<th class="px-6 py-4 border">

Status

</th>

<th class="px-6 py-4 border">

Actions

</th>

</tr>

</thead>

<tbody>

<?php

if(empty($assignments))
{

?>

<tr>

<td
colspan="8"
class="text-center py-12 text-gray-500">

No Asset Assignments Found

</td>

</tr>

<?php

}

$i = 1;

foreach($assignments as $row):

?>

<tr class="hover:bg-gray-50">

<td class="border px-5 py-4">

<?= $i++; ?>

</td>

<td class="border px-5 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['employee_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['employee_code']); ?>

</div>

</td>

<td class="border px-5 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['asset_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['brand']); ?>

<?= htmlspecialchars($row['model']); ?>

</div>

</td>

<td class="border px-5 py-4">

<?= htmlspecialchars($row['asset_tag']); ?>

</td>
<td class="border px-5 py-4 text-center">

<?= date('d M Y', strtotime($row['assigned_date'])); ?>

</td>

<td class="border px-5 py-4 text-center">

<?php

if(!empty($row['expected_return_date']))
{
    echo date(
        'd M Y',
        strtotime($row['expected_return_date'])
    );
}
else
{
    echo "<span class='text-gray-400'>N/A</span>";
}

?>

</td>

<td class="border px-5 py-4 text-center">

<?php if($row['status']=="Assigned"): ?>

<span
class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">

Assigned

</span>

<?php else: ?>

<span
class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">

Returned

</span>

<?php endif; ?>

</td>

<td class="border px-5 py-4">

<div class="flex justify-center gap-2">

<a
href="?page=asset-assignment-show&id=<?= $row['id']; ?>"
class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">

View

</a>

<a
href="?page=asset-assignment-edit&id=<?= $row['id']; ?>"
class="rounded-lg bg-yellow-500 px-3 py-2 text-sm font-medium text-white hover:bg-yellow-600">

Edit

</a>

<a
href="?page=asset-assignment-return&id=<?= $row['id']; ?>"
class="rounded-lg bg-green-600 px-3 py-2 text-sm font-medium text-white hover:bg-green-700">

Return

</a>

<a
href="?page=asset-assignment-delete&id=<?= $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this assignment?')"
class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">

Delete

</a>

</div>

</td>

</tr>

<?php endforeach; ?>
</tbody>

</table>

</div>

<!-- Summary -->

<div class="flex items-center justify-between mt-6 pt-4 border-t">

<div class="text-sm text-gray-500">

Showing

<strong>

<?= count($assignments); ?>

</strong>

assignment(s)

</div>

<div>

<a
href="?page=dashboard"
class="inline-flex items-center rounded-lg bg-gray-700 px-5 py-2 text-white hover:bg-gray-800 transition">

← Back to Dashboard

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>