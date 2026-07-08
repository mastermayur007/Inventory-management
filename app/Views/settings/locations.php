<?php

require_once __DIR__."/../../../config/database.php";

$db=(new Database())->connect();

/*
|--------------------------------------------------------------------------
| Add Location
|--------------------------------------------------------------------------
*/

if($_SERVER['REQUEST_METHOD']=="POST")
{

$name=trim($_POST['location_name']);
$code=strtoupper(trim($_POST['location_code']));

$check=$db->prepare("
SELECT id
FROM locations
WHERE location_name=:name
OR location_code=:code
");

$check->execute([

'name'=>$name,

'code'=>$code

]);

if(!$check->fetch())
{

$stmt=$db->prepare("

INSERT INTO locations
(

location_name,

location_code,

address,

city,

state,

country,

postal_code,

contact_person,

contact_phone,

status

)

VALUES
(

:name,

:code,

:address,

:city,

:state,

:country,

:postal,

:person,

:phone,

:status

)

");

$stmt->execute([

'name'=>$name,

'code'=>$code,

'address'=>$_POST['address'],

'city'=>$_POST['city'],

'state'=>$_POST['state'],

'country'=>$_POST['country'],

'postal'=>$_POST['postal_code'],

'person'=>$_POST['contact_person'],

'phone'=>$_POST['contact_phone'],

'status'=>$_POST['status']

]);

header("Location:?page=settings-locations&success=1");

exit;

}

$error="Location already exists.";

}

$search=$_GET['search'] ?? '';

$stmt=$db->prepare("

SELECT *

FROM locations

WHERE

location_name LIKE :search

OR city LIKE :search

ORDER BY location_name

");

$stmt->execute([

'search'=>"%{$search}%"

]);

$locations=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Office Locations

</title>

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

📍 Office Locations

</h1>

<p class="text-gray-500">

Manage Company Locations

</p>

</div>

<a
href="?page=settings"
class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">

← Back

</a>

</div>

<?php if(isset($_GET['success'])): ?>

<div class="bg-green-100 border border-green-500 text-green-700 p-4 rounded-lg mb-6">

✅ Location added successfully.

</div>

<?php endif; ?>

<?php if(isset($error)): ?>

<div class="bg-red-100 border border-red-500 text-red-700 p-4 rounded-lg mb-6">

<?= htmlspecialchars($error); ?>

</div>

<?php endif; ?>

<!-- Dashboard Card -->

<div class="grid grid-cols-3 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Total Locations

</p>

<h2 class="text-4xl font-bold text-blue-700">

<?= count($locations); ?>

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
value="settings-locations">

<div class="flex gap-4">

<input
type="text"
name="search"
value="<?= htmlspecialchars($search); ?>"
placeholder="Search by Location or City..."
class="flex-1 border rounded-lg px-4 py-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

Search

</button>

<a
href="?page=settings-locations"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</form>

</div>

</div>

<!-- Add Location -->

<div class="bg-white rounded-xl shadow mb-8">

<div class="border-b px-6 py-4">

<h2 class="text-2xl font-bold">

Add New Location

</h2>

</div>

<form method="POST">

<div class="grid grid-cols-2 gap-6 p-6">
    <div>

<label class="block mb-2 font-semibold">

Location Name

</label>

<input
type="text"
name="location_name"
required
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Location Code

</label>

<input
type="text"
name="location_code"
required
placeholder="MUM-HO"
class="w-full border rounded-lg px-4 py-3">

</div>

<div class="col-span-2">

<label class="block mb-2 font-semibold">

Address

</label>

<textarea
name="address"
rows="3"
class="w-full border rounded-lg px-4 py-3"></textarea>

</div>

<div>

<label class="block mb-2 font-semibold">

City

</label>

<input
type="text"
name="city"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

State

</label>

<input
type="text"
name="state"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Country

</label>

<input
type="text"
name="country"
value="India"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Postal Code

</label>

<input
type="text"
name="postal_code"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Contact Person

</label>

<input
type="text"
name="contact_person"
class="w-full border rounded-lg px-4 py-3">

</div>

<div>

<label class="block mb-2 font-semibold">

Contact Phone

</label>

<input
type="text"
name="contact_phone"
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

<div class="col-span-2">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

+ Add Location

</button>

</div>

</div>

</form>

</div>

<!-- Locations Table -->

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white">

<tr>

<th class="px-4 py-4">#</th>

<th class="px-4 py-4">Location</th>

<th class="px-4 py-4">City</th>

<th class="px-4 py-4">Contact</th>

<th class="px-4 py-4">Status</th>

<th class="px-4 py-4">Action</th>

</tr>

</thead>

<tbody>
    <?php if(count($locations)>0): ?>

<?php foreach($locations as $index=>$location): ?>

<tr class="border-b hover:bg-blue-50 transition">

<td class="px-4 py-4">

<?= $index+1; ?>

</td>

<td class="px-4 py-4">

<div class="font-semibold text-blue-700">

<?= htmlspecialchars($location['location_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($location['location_code']); ?>

</div>

</td>

<td class="px-4 py-4">

<div>

<?= htmlspecialchars($location['city']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($location['state']); ?>

</div>

</td>

<td class="px-4 py-4">

<div>

<?= htmlspecialchars($location['contact_person']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($location['contact_phone']); ?>

</div>

</td>

<td class="px-4 py-4 text-center">

<?php if($location['status']=="Active"): ?>

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">

Active

</span>

<?php else: ?>

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">

Inactive

</span>

<?php endif; ?>

</td>

<td class="px-4 py-4">

<div class="flex gap-2 justify-center">

<a
href="?page=location-edit&id=<?= $location['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">

Edit

</a>

<a
href="?page=location-delete&id=<?= $location['id']; ?>"
onclick="return confirm('Are you sure you want to delete this location?');"
class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">

Delete

</a>

</div>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center py-16">

<div class="flex flex-col items-center">

<div class="text-6xl mb-4">

📍

</div>

<h2 class="text-2xl font-bold text-gray-600">

No Locations Found

</h2>

<p class="text-gray-500 mt-2">

Create your first office location.

</p>

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<!-- Information -->

<div class="bg-blue-50 border border-blue-200 rounded-xl mt-8 p-6">

<h2 class="text-xl font-bold text-blue-800 mb-4">

Office Location Guidelines

</h2>

<ul class="list-disc list-inside space-y-2 text-gray-700">

<li>Every office should have a unique Location Code.</li>

<li>Assets can be assigned to locations.</li>

<li>Employees can belong to a location.</li>

<li>Reports can be filtered by location.</li>

<li>Inactive locations won't appear in dropdowns.</li>

</ul>

</div>

<!-- Footer -->

<div class="mt-10 text-center text-gray-500 text-sm">

IT Asset Management System © <?= date('Y'); ?>

</div>

</div>

</div>

</div>

</body>

</html>