<?php

require_once __DIR__."/../../../config/database.php";

$db=(new Database())->connect();

/*
|--------------------------------------------------------------------------
| Add Manufacturer
|--------------------------------------------------------------------------
*/

if($_SERVER['REQUEST_METHOD']=="POST")
{

$name=trim($_POST['manufacturer_name']);

$check=$db->prepare("
SELECT id
FROM manufacturers
WHERE manufacturer_name=:name
");

$check->execute([
'name'=>$name
]);

if(!$check->fetch())
{

$stmt=$db->prepare("

INSERT INTO manufacturers
(
manufacturer_name,
country,
website,
support_email,
support_phone,
status
)

VALUES
(
:name,
:country,
:website,
:email,
:phone,
:status
)

");

$stmt->execute([

'name'=>$name,

'country'=>$_POST['country'],

'website'=>$_POST['website'],

'email'=>$_POST['support_email'],

'phone'=>$_POST['support_phone'],

'status'=>$_POST['status']

]);

header("Location:?page=settings-manufacturers&success=1");

exit;

}

$error="Manufacturer already exists.";

}

$search=$_GET['search'] ?? '';

$stmt=$db->prepare("

SELECT *

FROM manufacturers

WHERE manufacturer_name LIKE :search

ORDER BY manufacturer_name

");

$stmt->execute([

'search'=>"%{$search}%"

]);

$manufacturers=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manufacturers</title>

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

🏭 Manufacturers

</h1>

<p class="text-gray-500">

Manage Asset Manufacturers

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

Manufacturer added successfully.

</div>

<?php endif; ?>

<?php if(isset($error)): ?>

<div class="bg-red-100 border border-red-500 text-red-700 p-4 rounded mb-6">

<?= $error ?>

</div>

<?php endif; ?>

<!-- Summary -->

<div class="grid grid-cols-3 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Total Manufacturers

</p>

<h2 class="text-4xl font-bold text-blue-700">

<?= count($manufacturers); ?>

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
value="settings-manufacturers">

<div class="flex gap-4">

<input
type="text"
name="search"
value="<?= htmlspecialchars($search); ?>"
placeholder="Search Manufacturer..."
class="flex-1 border rounded-lg px-4 py-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Search

</button>

<a
href="?page=settings-manufacturers"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</form>

</div>

</div>

<!-- Add Manufacturer -->

<div class="bg-white rounded-xl shadow mb-8">

<div class="border-b px-6 py-4">

<h2 class="text-2xl font-bold">

Add Manufacturer

</h2>

</div>

<form method="POST">

<div class="grid grid-cols-2 gap-6 p-6">
    <div>

<label class="block mb-2 font-semibold">

Manufacturer Name

</label>

<input
type="text"
name="manufacturer_name"
required
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Country

</label>

<input
type="text"
name="country"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Website

</label>

<input
type="url"
name="website"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Support Email

</label>

<input
type="email"
name="support_email"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Support Phone

</label>

<input
type="text"
name="support_phone"
class="w-full border rounded-lg px-4 py-3">

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

</div>

<div class="px-6 pb-6">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

+ Add Manufacturer

</button>

</div>

</form>

</div>

<!-- Manufacturers Table -->

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white">

<tr>

<th class="px-4 py-4">

#

</th>

<th class="px-4 py-4">

Manufacturer

</th>

<th class="px-4 py-4">

Country

</th>

<th class="px-4 py-4">

Email

</th>

<th class="px-4 py-4">

Phone

</th>

<th class="px-4 py-4">

Status

</th>

<th class="px-4 py-4">

Action

</th>

</tr>

</thead>

<tbody>
    <?php if(count($manufacturers)>0): ?>

<?php foreach($manufacturers as $index=>$m): ?>

<tr class="border-b hover:bg-blue-50">

<td class="px-4 py-4">

<?= $index+1; ?>

</td>

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($m['manufacturer_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($m['website']); ?>

</div>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($m['country']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($m['support_email']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($m['support_phone']); ?>

</td>

<td class="px-4 py-4">

<?php if($m['status']=="Active"): ?>

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
href="?page=manufacturer-edit&id=<?= $m['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded">

Edit

</a>

<a
href="?page=manufacturer-delete&id=<?= $m['id']; ?>"
onclick="return confirm('Delete manufacturer?');"
class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded ml-2">

Delete

</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="7" class="text-center py-12 text-gray-500">

No manufacturers found.

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<div class="mt-10 text-center text-gray-500 text-sm">

IT Asset Management System © <?= date('Y'); ?>

</div>

</div>

</div>

</div>

</body>

</html>