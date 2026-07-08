<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

/*
|--------------------------------------------------------------------------
| Search & Filter
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');

$sql = "

SELECT

aa.*,

a.asset_id,
a.asset_tag,
a.asset_name,
a.serial_number,

e.employee_code,
CONCAT(e.first_name,' ',e.last_name) AS employee_name

FROM asset_assignments aa

INNER JOIN assets a
ON aa.asset_id = a.id

INNER JOIN employees e
ON aa.employee_id = e.id

WHERE 1=1

";

$params = [];

if($search != "")
{

$sql .= "

AND (

a.asset_id LIKE :search

OR a.asset_tag LIKE :search

OR a.serial_number LIKE :search

OR CONCAT(e.first_name,' ',e.last_name) LIKE :search

OR e.employee_code LIKE :search

)

";

$params['search'] = "%{$search}%";

}

if($status != "")
{

$sql .= "

AND aa.status = :status

";

$params['status'] = $status;

}

$sql .= "

ORDER BY aa.id DESC

";

$stmt = $db->prepare($sql);

$stmt->execute($params);

$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Dashboard Cards
|--------------------------------------------------------------------------
*/

$totalAssignments = count($assignments);

$assignedCount = 0;
$returnedCount = 0;

foreach ($assignments as $row)
{
    if ($row['status'] == "Assigned")
    {
        $assignedCount++;
    }
    else
    {
        $returnedCount++;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Asset Assignments</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="p-8">

<div class="bg-white rounded-xl shadow-lg">

<div class="flex justify-between items-center border-b p-6">

<div>

<h1 class="text-3xl font-bold text-gray-800">

Asset Assignments

</h1>

<p class="text-gray-500 mt-2">

Manage Assigned Company Assets

</p>

</div>

<a
href="?page=asset-assignment-create"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow">

+ Assign Asset

</a>

</div>

<!-- Dashboard Cards -->

<div class="grid grid-cols-3 gap-6 p-6">

<div class="bg-blue-50 rounded-xl p-5">

<p class="text-gray-500">

Total Assignments

</p>

<h2 class="text-3xl font-bold text-blue-700">

<?= $totalAssignments ?>

</h2>

</div>

<div class="bg-green-50 rounded-xl p-5">

<p class="text-gray-500">

Assigned

</p>

<h2 class="text-3xl font-bold text-green-700">

<?= $assignedCount ?>

</h2>

</div>

<div class="bg-indigo-50 rounded-xl p-5">

<p class="text-gray-500">

Returned

</p>

<h2 class="text-3xl font-bold text-indigo-700">

<?= $returnedCount ?>

</h2>

</div>

</div>

<!-- Search -->

<div class="px-6 pb-6">

<form method="GET">

<input
type="hidden"
name="page"
value="asset-assignments">

<div class="flex gap-4">

<input
type="text"
name="search"
value="<?= htmlspecialchars($search) ?>"
placeholder="Search Asset ID, Asset Tag, Employee..."
class="flex-1 border rounded-lg px-4 py-3">

<select
name="status"
class="border rounded-lg px-4 py-3">

<option value="">

All Status

</option>

<option
value="Assigned"
<?= $status=="Assigned"?"selected":""; ?>>

Assigned

</option>

<option
value="Returned"
<?= $status=="Returned"?"selected":""; ?>>

Returned

</option>

</select>

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

Search

</button>

<a
href="?page=asset-assignments"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</form>

</div>

<div class="overflow-x-auto">

<table class="min-w-full border-collapse">
<thead>

<tr class="bg-gray-800 text-white uppercase text-sm">

<th class="px-4 py-4 text-left">

#

</th>

<th class="px-4 py-4 text-left">

Asset ID

</th>

<th class="px-4 py-4 text-left">

Employee

</th>

<th class="px-4 py-4 text-left">

Asset

</th>

<th class="px-4 py-4 text-left">

Assigned Date

</th>

<th class="px-4 py-4 text-left">

Expected Return

</th>

<th class="px-4 py-4 text-center">

Status

</th>

<th class="px-4 py-4 text-center">

Actions

</th>

</tr>

</thead>

<tbody>

<?php if(count($assignments)>0): ?>

<?php

$i = 1;

foreach($assignments as $row):

?>

<tr class="border-b hover:bg-blue-50 transition">

<td class="px-4 py-4 font-semibold">

<?= $i++; ?>

</td>

<td class="px-4 py-4">

<div class="font-bold text-blue-700">

<?= htmlspecialchars($row['asset_id']); ?>

</div>

<div class="text-xs text-gray-500">

<?= htmlspecialchars($row['asset_tag']); ?>

</div>

</td>

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['employee_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['employee_code']); ?>

</div>

</td>

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['asset_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['serial_number']); ?>

</div>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($row['assigned_date']); ?>

</td>

<td class="px-4 py-4">

<?= !empty($row['expected_return_date'])
? htmlspecialchars($row['expected_return_date'])
: '-'; ?>

</td>

<td class="px-4 py-4 text-center">

<?php

switch($row['status'])
{

case 'Assigned':

echo '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">

Assigned

</span>';

break;

case 'Returned':

echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">

Returned

</span>';

break;

default:

echo '<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">'

. htmlspecialchars($row['status']) .

'</span>';

break;

}

?>

</td>

<td class="px-4 py-4">

<div class="flex justify-center gap-2">
<!-- View -->

<a
href="?page=asset-assignment-show&id=<?= $row['id']; ?>"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg text-sm transition">

View

</a>

<!-- Edit -->

<a
href="?page=asset-assignment-edit&id=<?= $row['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm transition">

Edit

</a>

<!-- Return -->

<?php if($row['status']=="Assigned"): ?>

<a
href="?page=asset-assignment-return&id=<?= $row['id']; ?>"
class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm transition">

Return

</a>

<?php endif; ?>

<!-- Delete -->

<a
href="?page=asset-assignment-delete&id=<?= $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this assignment?');"
class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm transition">

Delete

</a>

</div>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td
colspan="8"
class="text-center py-12">

<div class="flex flex-col items-center">

<div class="text-6xl mb-4">

📋

</div>

<h2 class="text-2xl font-bold text-gray-600">

No Asset Assignments Found

</h2>

<p class="text-gray-500 mt-2">

Assign an asset to an employee to get started.

</p>

<a
href="?page=asset-assignment-create"
class="mt-5 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

+ Assign Asset

</a>

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const search = document.querySelector("input[name='search']");

    if(search){

        search.focus();

    }

});

</script>

</body>

</html>