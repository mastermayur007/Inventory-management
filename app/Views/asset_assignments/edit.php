<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$id = $_GET['id'] ?? 0;

/*
|--------------------------------------------------------------------------
| Get Assignment
|--------------------------------------------------------------------------
*/

$sql = "
SELECT *
FROM asset_assignments
WHERE id=:id
";

$stmt = $db->prepare($sql);

$stmt->execute([
    'id'=>$id
]);

$assignment = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$assignment){
    die("Assignment not found.");
}

/*
|--------------------------------------------------------------------------
| Employees
|--------------------------------------------------------------------------
*/

$employees = $db->query("
SELECT
id,
employee_code,
first_name,
last_name
FROM employees
WHERE status='Active'
ORDER BY first_name
")->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Assets
|--------------------------------------------------------------------------
*/

$assets = $db->query("
SELECT
id,
asset_tag,
asset_name,
brand,
model
FROM assets
ORDER BY asset_name
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width,initial-scale=1.0">

<title>Edit Asset Assignment</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

<?php require_once "../layouts/sidebar.php"; ?>

<div class="flex-1 p-8">

<div class="bg-white rounded-lg shadow-lg">

<div class="border-b p-6">

<h1 class="text-2xl font-bold">

Edit Asset Assignment

</h1>

<p class="text-gray-500">

Update Assigned Asset Information

</p>

</div>

<div class="p-6">

<form
method="POST"
action="?page=asset-assignment-update&id=<?= $assignment['id']; ?>">

<div class="grid grid-cols-2 gap-6">

<!-- Employee -->

<div>

<label class="block mb-2 font-semibold">

Employee

</label>

<select
name="employee_id"
class="w-full border rounded-lg p-3"
required>

<?php foreach($employees as $employee): ?>

<option
value="<?= $employee['id']; ?>"

<?= ($employee['id']==$assignment['employee_id']) ? 'selected' : ''; ?>

>

<?= $employee['employee_code']; ?>

-

<?= $employee['first_name']; ?>

<?= $employee['last_name']; ?>

</option>

<?php endforeach; ?>

</select>

</div>

<!-- Asset -->

<div>

<label class="block mb-2 font-semibold">

Asset

</label>

<select
name="asset_id"
class="w-full border rounded-lg p-3"
required>

<?php foreach($assets as $asset): ?>

<option
value="<?= $asset['id']; ?>"

<?= ($asset['id']==$assignment['asset_id']) ? 'selected' : ''; ?>

>

<?= $asset['asset_tag']; ?>

-

<?= $asset['asset_name']; ?>

</option>

<?php endforeach; ?>

</select>

</div>

<!-- Assigned By -->

<div>

<label class="block mb-2 font-semibold">

Assigned By

</label>

<input
type="text"
name="assigned_by"
class="w-full border rounded-lg p-3"
value="<?= $assignment['assigned_by']; ?>"
required>

</div>

<!-- Assigned Date -->

<div>

<label class="block mb-2 font-semibold">

Assigned Date

</label>

<input
type="date"
name="assigned_date"
class="w-full border rounded-lg p-3"
value="<?= $assignment['assigned_date']; ?>"
required>

</div>

<!-- Expected Return -->

<div>

<label class="block mb-2 font-semibold">

Expected Return Date

</label>

<input
type="date"
name="expected_return_date"
class="w-full border rounded-lg p-3"
value="<?= $assignment['expected_return_date']; ?>">

</div>

<!-- Status -->

<div>

<label class="block mb-2 font-semibold">

Status

</label>

<select
name="status"
class="w-full border rounded-lg p-3">

<option
value="Assigned"

<?= ($assignment['status']=="Assigned") ? "selected" : ""; ?>

>

Assigned

</option>

<option
value="Returned"

<?= ($assignment['status']=="Returned") ? "selected" : ""; ?>

>

Returned

</option>

</select>

</div>

</div>

<!-- Remarks -->

<div class="mt-6">

<label class="block mb-2 font-semibold">

Remarks

</label>

<textarea
name="remarks"
rows="5"
class="w-full border rounded-lg p-3"><?= $assignment['remarks']; ?></textarea>

</div>

<!-- Buttons -->

<div class="flex justify-end mt-8 gap-3">

<a
href="?page=asset-assignments"
class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">

Cancel

</a>

<button
type="submit"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">

Update Assignment

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</body>

</html>