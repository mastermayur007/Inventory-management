<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$id = $_GET['id'] ?? 0;

$stmt = $db->prepare("
    SELECT
        aa.*,
        a.asset_tag,
        a.asset_name,
        e.employee_code,
        CONCAT(e.first_name,' ',e.last_name) AS employee_name
    FROM asset_assignments aa
    INNER JOIN assets a
        ON aa.asset_id = a.id
    INNER JOIN employees e
        ON aa.employee_id = e.id
    WHERE aa.id = :id
");

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

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Delete Assignment</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="max-w-3xl mx-auto mt-10">

<div class="bg-white rounded-xl shadow-lg">

<div class="bg-red-600 text-white px-6 py-4 rounded-t-xl">

<h1 class="text-2xl font-bold">

Delete Asset Assignment

</h1>

</div>

<div class="p-6">

<p class="text-lg text-gray-700">

Are you sure you want to delete this assignment?

</p>

<div class="mt-6 space-y-3">

<div>

<strong>Employee:</strong>

<?= htmlspecialchars($assignment['employee_name']); ?>

</div>

<div>

<strong>Employee Code:</strong>

<?= htmlspecialchars($assignment['employee_code']); ?>

</div>

<div>

<strong>Asset:</strong>

<?= htmlspecialchars($assignment['asset_name']); ?>

</div>

<div>

<strong>Asset Tag:</strong>

<?= htmlspecialchars($assignment['asset_tag']); ?>

</div>

<div>

<strong>Status:</strong>

<?= htmlspecialchars($assignment['status']); ?>

</div>

</div>

<form
method="POST"
action="?page=asset-assignment-delete&id=<?= $assignment['id']; ?>"
class="mt-8">

<div class="flex justify-end gap-3">

<a
href="?page=asset-assignments"
class="px-5 py-3 rounded-lg bg-gray-500 text-white hover:bg-gray-600">

Cancel

</a>

<button
type="submit"
class="px-5 py-3 rounded-lg bg-red-600 text-white hover:bg-red-700">

Delete Assignment

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>