<?php

require_once __DIR__ . "/../../Controllers/AssetAssignmentController.php";
require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$employees = $db->query("
    SELECT
        id,
        employee_code,
        first_name,
        last_name
    FROM employees
    WHERE status='Active'
    ORDER BY first_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

$assets = $db->query("
    SELECT
        id,
        asset_tag,
        asset_name,
        brand,
        model
    FROM assets
    WHERE status='Available'
    ORDER BY asset_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Assign Asset</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

    <?php require_once "../layouts/sidebar.php"; ?>

    <div class="flex-1 p-8">

        <div class="bg-white rounded-lg shadow-md">

            <div class="border-b p-6">

                <h1 class="text-2xl font-bold text-gray-800">

                    Asset Assignment

                </h1>

                <p class="text-gray-500">

                    Assign Company Asset to Employee

                </p>

            </div>

            <div class="p-6">

<?php if(isset($_SESSION['success'])): ?>

<div class="mb-4 p-4 rounded bg-green-100 text-green-700">

<?= $_SESSION['success']; ?>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>

<div class="mb-4 p-4 rounded bg-red-100 text-red-700">

<?= $_SESSION['error']; ?>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>

<form
method="POST"
action="?page=asset-assignment-store">

<div class="grid grid-cols-2 gap-6">

<div>

<label
class="block text-sm font-semibold mb-2">

Employee

</label>

<select
name="employee_id"
required
class="w-full border rounded-lg p-3">

<option value="">

Select Employee

</option>

<?php foreach($employees as $employee): ?>

<option value="<?= $employee['id']; ?>">

<?= $employee['employee_code']; ?>

-

<?= $employee['first_name']; ?>

<?= $employee['last_name']; ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div>

<label
class="block text-sm font-semibold mb-2">

Asset

</label>

<select
name="asset_id"
required
class="w-full border rounded-lg p-3">

<option value="">

Select Asset

</option>

<?php foreach($assets as $asset): ?>

<option value="<?= $asset['id']; ?>">

<?= $asset['asset_tag']; ?>

-

<?= $asset['asset_name']; ?>

(

<?= $asset['brand']; ?>

<?= $asset['model']; ?>

)

</option>

<?php endforeach; ?>

</select>

</div>
<!-- Assigned By -->
<div>

    <label class="block text-sm font-semibold mb-2">
        Assigned By
    </label>

    <input
        type="text"
        name="assigned_by"
        class="w-full border rounded-lg p-3"
        placeholder="IT Administrator"
        value="<?= $_SESSION['username'] ?? 'Administrator'; ?>"
        required>

</div>

<!-- Assigned Date -->
<div>

    <label class="block text-sm font-semibold mb-2">
        Assigned Date
    </label>

    <input
        type="date"
        name="assigned_date"
        class="w-full border rounded-lg p-3"
        value="<?= date('Y-m-d'); ?>"
        required>

</div>

<!-- Expected Return Date -->
<div>

    <label class="block text-sm font-semibold mb-2">
        Expected Return Date
    </label>

    <input
        type="date"
        name="expected_return_date"
        class="w-full border rounded-lg p-3">

</div>

<!-- Status -->
<div>

    <label class="block text-sm font-semibold mb-2">
        Status
    </label>

    <input
        type="text"
        class="w-full border rounded-lg p-3 bg-gray-100"
        value="Assigned"
        readonly>

</div>

</div>

<!-- Remarks -->

<div class="mt-6">

    <label class="block text-sm font-semibold mb-2">
        Remarks
    </label>

    <textarea
        name="remarks"
        rows="4"
        class="w-full border rounded-lg p-3"
        placeholder="Enter remarks if any..."></textarea>

</div>

<!-- Buttons -->

<div class="flex justify-end mt-8 space-x-3">

    <a
        href="?page=asset-assignments"
        class="px-5 py-3 rounded-lg bg-gray-500 text-white hover:bg-gray-600">

        Cancel

    </a>

    <button
        type="submit"
        class="px-5 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

        Assign Asset

    </button>

</div>

</form>

</div>

</div>

</div>

</div>

</body>

</html>