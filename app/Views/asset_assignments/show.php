<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$id = $_GET['id'] ?? 0;

$sql = "
SELECT

aa.*,

a.asset_tag,
a.asset_name,
a.brand,
a.model,
a.serial_number,
a.category,

e.employee_code,
e.first_name,
e.last_name,
e.designation,
e.email,
e.mobile

FROM asset_assignments aa

INNER JOIN assets a
ON aa.asset_id = a.id

INNER JOIN employees e
ON aa.employee_id = e.id

WHERE aa.id = :id
";

$stmt = $db->prepare($sql);

$stmt->execute([
    'id' => $id
]);

$assignment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$assignment) {
    die("Assignment not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Assignment Details</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="max-w-7xl mx-auto p-8">

<div class="bg-white rounded-lg shadow-lg">

<div class="border-b p-6 flex justify-between">

<div>

<h1 class="text-2xl font-bold">

Asset Assignment Details

</h1>

<p class="text-gray-500">

Assignment #<?= $assignment['id']; ?>

</p>

</div>

<a
href="?page=asset-assignments"
class="bg-gray-600 text-white px-4 py-2 rounded">

Back

</a>

</div>

<div class="grid grid-cols-2 gap-8 p-8">

<!-- Employee -->

<div>

<h2 class="text-xl font-semibold mb-4">

Employee Information

</h2>

<table class="w-full">

<tr>

<td class="font-semibold py-2">

Employee Code

</td>

<td>

<?= $assignment['employee_code']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Name

</td>

<td>

<?= $assignment['first_name']; ?>

<?= $assignment['last_name']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Designation

</td>

<td>

<?= $assignment['designation']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Email

</td>

<td>

<?= $assignment['email']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Mobile

</td>

<td>

<?= $assignment['mobile']; ?>

</td>

</tr>

</table>

</div>

<!-- Asset -->

<div>

<h2 class="text-xl font-semibold mb-4">

Asset Information

</h2>

<table class="w-full">

<tr>

<td class="font-semibold py-2">

Asset Tag

</td>

<td>

<?= $assignment['asset_tag']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Asset

</td>

<td>

<?= $assignment['asset_name']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Brand

</td>

<td>

<?= $assignment['brand']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Model

</td>

<td>

<?= $assignment['model']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Serial Number

</td>

<td>

<?= $assignment['serial_number']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Category

</td>

<td>

<?= $assignment['category']; ?>

</td>

</tr>

</table>

</div>

</div>

<div class="border-t p-8">

<h2 class="text-xl font-semibold mb-5">

Assignment Information

</h2>

<table class="w-full">

<tr>

<td class="font-semibold py-2">

Assigned By

</td>

<td>

<?= $assignment['assigned_by']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Assigned Date

</td>

<td>

<?= $assignment['assigned_date']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Expected Return

</td>

<td>

<?= $assignment['expected_return_date']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Actual Return

</td>

<td>

<?= $assignment['actual_return_date']; ?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Status

</td>

<td>

<?php

if($assignment['status']=="Assigned")
{

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">Assigned</span>';

}
else
{

echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">Returned</span>';

}

?>

</td>

</tr>

<tr>

<td class="font-semibold py-2">

Remarks

</td>

<td>

<?= nl2br($assignment['remarks']); ?>

</td>

</tr>

</table>

</div>

<div class="border-t p-6 flex justify-end gap-3">

<a
href="?page=asset-assignment-edit&id=<?= $assignment['id']; ?>"
class="bg-yellow-500 text-white px-5 py-3 rounded">

Edit

</a>

<a
href="?page=asset-assignment-return&id=<?= $assignment['id']; ?>"
class="bg-green-600 text-white px-5 py-3 rounded">

Return Asset

</a>

<button
onclick="window.print()"
class="bg-blue-600 text-white px-5 py-3 rounded">

Print

</button>

</div>

</div>

</div>

</div>

</div> <!-- max-w-7xl -->
</div> <!-- flex-1 -->
</div> <!-- flex -->
</body>

</html>