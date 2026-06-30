<?php
require_once __DIR__ . "/../../Controllers/AssetAssignmentController.php";
require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$employees = $db->query("
SELECT id, employee_code, first_name, last_name
FROM employees
WHERE status='Active'
ORDER BY first_name
")->fetchAll(PDO::FETCH_ASSOC);

$assets = $db->query("
SELECT id, asset_tag, asset_name, brand, model
FROM assets
WHERE status='Available'
ORDER BY asset_name
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Asset</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">
<div class="max-w-6xl mx-auto px-8 py-8">

<div class="bg-white rounded-xl shadow-lg overflow-hidden">

<div class="bg-gray-50 border-b px-8 py-6">
<h1 class="text-3xl font-bold text-gray-800">Assign Asset</h1>
<p class="text-sm text-gray-500 mt-1">Assign a company asset to an employee.</p>
</div>

<div class="p-8">

<?php if(isset($_SESSION['success'])): ?>
<div class="mb-5 rounded-lg bg-green-100 text-green-700 p-4">
<?= $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
<div class="mb-5 rounded-lg bg-red-100 text-red-700 p-4">
<?= $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<form method="POST" action="?page=asset-assignment-store">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<div>
<label class="block mb-2 font-semibold text-sm">Employee</label>
<select name="employee_id" required class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">
<option value="">Select Employee</option>
<?php foreach($employees as $employee): ?>
<option value="<?= $employee['id']; ?>">
<?= htmlspecialchars($employee['employee_code']); ?> - <?= htmlspecialchars($employee['first_name'].' '.$employee['last_name']); ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div>
<label class="block mb-2 font-semibold text-sm">Asset</label>
<select name="asset_id" required class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">
<option value="">Select Asset</option>
<?php foreach($assets as $asset): ?>
<option value="<?= $asset['id']; ?>">
<?= htmlspecialchars($asset['asset_tag']); ?> - <?= htmlspecialchars($asset['asset_name']); ?> (<?= htmlspecialchars($asset['brand'].' '.$asset['model']); ?>)
</option>
<?php endforeach; ?>
</select>
</div>

<div>
<label class="block mb-2 font-semibold text-sm">Assigned By</label>
<input type="text" name="assigned_by" required value="<?= $_SESSION['username'] ?? 'Administrator'; ?>" class="w-full rounded-lg border border-gray-300 px-4 py-3">
</div>

<div>
<label class="block mb-2 font-semibold text-sm">Assigned Date</label>
<input type="date" name="assigned_date" value="<?= date('Y-m-d'); ?>" required class="w-full rounded-lg border border-gray-300 px-4 py-3">
</div>

<div>
<label class="block mb-2 font-semibold text-sm">Expected Return Date</label>
<input type="date" name="expected_return_date" class="w-full rounded-lg border border-gray-300 px-4 py-3">
</div>

<div>
<label class="block mb-2 font-semibold text-sm">Status</label>
<input type="text" value="Assigned" readonly class="w-full rounded-lg border border-gray-300 bg-gray-100 text-green-700 font-semibold px-4 py-3">
</div>

<div class="md:col-span-2">
<label class="block mb-2 font-semibold text-sm">Remarks</label>
<textarea name="remarks" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-3" placeholder="Enter remarks..."></textarea>
</div>

</div>

<div class="flex justify-end gap-4 mt-8 pt-6 border-t">
<a href="?page=asset-assignments" class="px-6 py-3 rounded-lg bg-gray-500 hover:bg-gray-600 text-white">Cancel</a>
<button type="submit" class="px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow">Assign Asset</button>
</div>

</form>

</div>
</div>

</div>
</div>

</body>
</html>
