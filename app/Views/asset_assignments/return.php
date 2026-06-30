<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

    e.employee_code,
    CONCAT(e.first_name,' ',e.last_name) AS employee_name

FROM asset_assignments aa

INNER JOIN assets a
ON aa.asset_id = a.id

INNER JOIN employees e
ON aa.employee_id = e.id

WHERE aa.id = :id
";

$stmt = $db->prepare($sql);

$stmt->execute([
    'id'=>$id
]);

$assignment = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$assignment){
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

<title>Return Asset</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="max-w-6xl mx-auto p-8">

<div class="bg-white rounded-lg shadow-lg">

<div class="border-b p-6">

<h1 class="text-2xl font-bold text-red-600">

Return Asset

</h1>

<p class="text-gray-500">

Confirm Asset Return

</p>

</div>

<form
method="POST"
action="?page=asset-assignment-return-save&id=<?= $assignment['id']; ?>">

<div class="p-8">

<div class="grid grid-cols-2 gap-8">

<div>

<label class="font-semibold">

Employee

</label>

<input
type="text"
readonly
class="w-full mt-2 border rounded-lg p-3 bg-gray-100"
value="<?= $assignment['employee_name']; ?>">

</div>

<div>

<label class="font-semibold">

Employee Code

</label>

<input
type="text"
readonly
class="w-full mt-2 border rounded-lg p-3 bg-gray-100"
value="<?= $assignment['employee_code']; ?>">

</div>

<div>

<label class="font-semibold">

Asset

</label>

<input
type="text"
readonly
class="w-full mt-2 border rounded-lg p-3 bg-gray-100"
value="<?= $assignment['asset_name']; ?>">

</div>

<div>

<label class="font-semibold">

Asset Tag

</label>

<input
type="text"
readonly
class="w-full mt-2 border rounded-lg p-3 bg-gray-100"
value="<?= $assignment['asset_tag']; ?>">

</div>

<div>

<label class="font-semibold">

Brand

</label>

<input
type="text"
readonly
class="w-full mt-2 border rounded-lg p-3 bg-gray-100"
value="<?= $assignment['brand']; ?>">

</div>

<div>

<label class="font-semibold">

Model

</label>

<input
type="text"
readonly
class="w-full mt-2 border rounded-lg p-3 bg-gray-100"
value="<?= $assignment['model']; ?>">

</div>

<div>

<label class="font-semibold">

Assigned Date

</label>

<input
type="text"
readonly
class="w-full mt-2 border rounded-lg p-3 bg-gray-100"
value="<?= $assignment['assigned_date']; ?>">

</div>

<div>

<label class="font-semibold">

Return Date

</label>

<input
type="date"
name="actual_return_date"
class="w-full mt-2 border rounded-lg p-3"
value="<?= date('Y-m-d'); ?>"
required>

</div>

</div>

<div class="mt-6">

<label class="font-semibold">

Return Remarks

</label>

<textarea
name="remarks"
rows="5"
class="w-full mt-2 border rounded-lg p-3"
placeholder="Condition of returned asset..."></textarea>

</div>

<div
class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-6 rounded">

<strong>Important:</strong>

Returning this asset will automatically:

<ul class="list-disc ml-6 mt-2">

<li>Update assignment status to Returned</li>

<li>Change asset status to Available</li>

<li>Record the return date</li>

<li>Store return remarks</li>

</ul>

</div>

<div class="flex justify-end gap-3 mt-8">

<a
href="?page=asset-assignments"
class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg">

Cancel

</a>

<button
type="submit"
onclick="return confirm('Are you sure you want to return this asset?');"
class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg">

Return Asset

</button>

</div>

</div>

</form>

</div>

</div>

</div>
</div> <!-- max-w-6xl -->

</div> <!-- flex-1 -->

</div> <!-- flex -->

</body>

</body>

</html>