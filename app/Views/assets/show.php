<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$id = $_GET['id'] ?? 0;

$sql = "
SELECT

a.*,

v.company_name,
v.contact_person,
v.phone,
v.email

FROM assets a

LEFT JOIN vendors v
ON a.vendor_id = v.id

WHERE a.id = :id
";

$stmt = $db->prepare($sql);

$stmt->execute([
    'id' => $id
]);

$asset = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$asset)
{
    die("Asset Not Found");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Asset Details</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="max-w-7xl mx-auto p-8">
<div class="bg-white rounded-xl shadow-lg mb-6">

<div class="flex justify-between items-center p-6 border-b">

<div>

<h1 class="text-3xl font-bold">

Asset Details

</h1>

<p class="text-gray-500">

<?= htmlspecialchars($asset['asset_id']); ?>

</p>

</div>

<a
href="?page=assets"
class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-3 rounded-lg">

Back

</a>

</div>
<div class="grid grid-cols-2 gap-8 p-8">
<div>

<h2 class="text-xl font-bold mb-5">

Asset Information

</h2>

<table class="w-full">

<tr>

<td class="font-semibold py-3">

Asset ID

</td>

<td>

<?= htmlspecialchars($asset['asset_id']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Asset Tag

</td>

<td>

<?= htmlspecialchars($asset['asset_tag']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Asset Name

</td>

<td>

<?= htmlspecialchars($asset['asset_name']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Serial Number

</td>

<td>

<?= htmlspecialchars($asset['serial_number']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Brand

</td>

<td>

<?= htmlspecialchars($asset['brand']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Model

</td>

<td>

<?= htmlspecialchars($asset['model']); ?>

</td>

</tr>

</table>

</div>
<div>

<h2 class="text-xl font-bold mb-5">

Vendor Information

</h2>

<table class="w-full">

<tr>

<td class="font-semibold py-3">

Vendor

</td>

<td>

<?= htmlspecialchars($asset['company_name']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Contact

</td>

<td>

<?= htmlspecialchars($asset['contact_person']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Phone

</td>

<td>

<?= htmlspecialchars($asset['phone']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Email

</td>

<td>

<?= htmlspecialchars($asset['email']); ?>

</td>

</tr>

<tr>

<td class="font-semibold py-3">

Status

</td>

<td>

<?= htmlspecialchars($asset['status']); ?>

</td>

</tr>

</table>

</div>

</div>
<div class="border-t p-6 flex gap-3 justify-end">

<a
href="?page=asset-edit&id=<?= $asset['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-lg">

Edit

</a>

<a
href="?page=asset-assignments"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">

Assignments

</a>

<button
onclick="window.print()"
class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg">

Print

</button>

</div>

</div>
</div>

</div>

</div>

</body>

</html>        