<?php

require_once __DIR__."/../../../config/database.php";

$db=(new Database())->connect();

/*
|--------------------------------------------------------------------------
| Add Status
|--------------------------------------------------------------------------
*/

if($_SERVER['REQUEST_METHOD']=="POST")
{

$name=trim($_POST['status_name']);

$check=$db->prepare("

SELECT id

FROM asset_statuses

WHERE status_name=:name

");

$check->execute([

'name'=>$name

]);

if(!$check->fetch())
{

$stmt=$db->prepare("

INSERT INTO asset_statuses
(

status_name,

status_color,

description,

status_type,

status_order,

is_default,

status

)

VALUES
(

:name,

:color,

:description,

:type,

:order,

:default,

:status

)

");

$stmt->execute([

'name'=>$name,

'color'=>$_POST['status_color'],

'description'=>$_POST['description'],

'type'=>$_POST['status_type'],

'order'=>$_POST['status_order'],

'default'=>isset($_POST['is_default'])?1:0,

'status'=>$_POST['status']

]);

header("Location:?page=settings-status&success=1");

exit;

}

$error="Status already exists.";

}

$search=$_GET['search'] ?? '';

$stmt=$db->prepare("

SELECT *

FROM asset_statuses

WHERE status_name LIKE :search

ORDER BY status_order

");

$stmt->execute([

'search'=>"%{$search}%"

]);

$statuses=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Asset Status Management</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__.'/../layouts/sidebar.php'; ?>

<div class="flex-1 ml-64">

<div class="p-8">

<div class="flex justify-between items-center mb-8">

<div>

<h1 class="text-4xl font-bold">

📊 Asset Status Management

</h1>

<p class="text-gray-500">

Manage Asset Status Master

</p>

</div>

<a
href="?page=settings"
class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">

← Back

</a>

</div>

<?php if(isset($_GET['success'])): ?>

<div class="bg-green-100 border border-green-500 text-green-700 p-4 rounded mb-6">

Asset Status Added Successfully

</div>

<?php endif; ?>

<?php if(isset($error)): ?>

<div class="bg-red-100 border border-red-500 text-red-700 p-4 rounded mb-6">

<?= $error ?>

</div>

<?php endif; ?>

<div class="grid grid-cols-3 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p>Total Status</p>

<h2 class="text-4xl font-bold text-blue-700">

<?= count($statuses) ?>

</h2>

</div>

</div>

<div class="bg-white rounded-xl shadow mb-8">

<div class="p-6">

<form method="GET">

<input
type="hidden"
name="page"
value="settings-status">

<div class="flex gap-4">

<input
type="text"
name="search"
value="<?= htmlspecialchars($search) ?>"
placeholder="Search Status..."
class="flex-1 border rounded-lg px-4 py-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Search

</button>

<a
href="?page=settings-status"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</form>

</div>

</div>

<div class="bg-white rounded-xl shadow mb-8">

<div class="border-b px-6 py-4">

<h2 class="text-2xl font-bold">

Add Asset Status

</h2>

</div>

<form method="POST">

<div class="grid grid-cols-2 gap-6 p-6">
<div>

<label class="block mb-2 font-semibold">

Status Name

</label>

<input
type="text"
name="status_name"
required
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Color

</label>

<select
name="status_color"
class="w-full border rounded-lg px-4 py-3">

<option value="green">Green</option>
<option value="blue">Blue</option>
<option value="yellow">Yellow</option>
<option value="red">Red</option>
<option value="gray">Gray</option>
<option value="purple">Purple</option>

</select>

</div>

<div>

<label class="block mb-2 font-semibold">

Status Type

</label>

<select
name="status_type"
class="w-full border rounded-lg px-4 py-3">

<option value="Available">

Available

</option>

<option value="Unavailable">

Unavailable

</option>

<option value="Maintenance">

Maintenance

</option>

<option value="Disposed">

Disposed

</option>

</select>

</div>

<div>

<label class="block mb-2 font-semibold">

Display Order

</label>

<input
type="number"
name="status_order"
value="1"
class="w-full border rounded-lg px-4 py-3">

</div>

<div class="col-span-2">

<label class="block mb-2 font-semibold">

Description

</label>

<textarea
name="description"
rows="3"
class="w-full border rounded-lg px-4 py-3"></textarea>

</div>

<div>

<label class="flex items-center gap-3">

<input
type="checkbox"
name="is_default">

Default Status

</label>

</div>

<div>

<label class="block mb-2 font-semibold">

Status

</label>

<select
name="status"
class="w-full border rounded-lg px-4 py-3">

<option value="Active">

Active

</option>

<option value="Inactive">

Inactive

</option>

</select>

</div>

<div class="col-span-2">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

+ Add Status

</button>

</div>

</div>

</form>

</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white">

<tr>

<th>#</th>

<th>Status</th>

<th>Color</th>

<th>Type</th>

<th>Order</th>

<th>Default</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody>
<?php if(count($statuses)>0): ?>

<?php foreach($statuses as $index=>$row): ?>

<tr class="border-b hover:bg-blue-50">

<td class="px-4 py-4">

<?= $index+1 ?>

</td>

<td class="px-4 py-4 font-semibold">

<?= htmlspecialchars($row['status_name']) ?>

</td>

<td class="px-4 py-4">

<span class="capitalize">

<?= htmlspecialchars($row['status_color']) ?>

</span>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($row['status_type']) ?>

</td>

<td class="px-4 py-4">

<?= $row['status_order'] ?>

</td>

<td class="px-4 py-4">

<?= $row['is_default'] ? '✅ Yes' : '❌ No' ?>

</td>

<td class="px-4 py-4">

<?php if($row['status']=="Active"): ?>

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

Active

</span>

<?php else: ?>

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

Inactive

</span>

<?php endif; ?>

</td>

<td class="px-4 py-4">

<a
href="?page=asset-status-edit&id=<?= $row['id'] ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded">

Edit

</a>

<a
href="?page=asset-status-delete&id=<?= $row['id'] ?>"
class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded ml-2">

Delete

</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="8" class="text-center py-10">

No Asset Status Found

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">

<h3 class="text-xl font-bold mb-3">

Asset Status Guidelines

</h3>

<ul class="list-disc list-inside space-y-2 text-gray-700">

<li>Create custom statuses like Lost, Reserved or In Transit.</li>

<li>Only one status should normally be marked as the default.</li>

<li>Inactive statuses won't appear in asset forms.</li>

<li>Display Order controls the dropdown sequence.</li>

</ul>

</div>

<div class="mt-10 text-center text-gray-500 text-sm">

IT Asset Management System © <?= date('Y') ?>

</div>

</div>

</div>

</div>

</body>

</html>        