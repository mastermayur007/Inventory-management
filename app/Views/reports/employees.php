<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');

$sql = "

SELECT

aa.*,

a.asset_id,
a.asset_tag,
a.asset_name,
a.serial_number,

e.employee_code,
e.first_name,
e.last_name,
e.designation,

d.department_name AS department

FROM asset_assignments aa

INNER JOIN assets a
ON aa.asset_id = a.id

INNER JOIN employees e
ON aa.employee_id = e.id

LEFT JOIN departments d
ON e.department_id = d.id

WHERE 1=1

";

$params = [];

if($search!="")
{

$sql.="

AND (

e.employee_code LIKE :search

OR CONCAT(e.first_name,' ',e.last_name) LIKE :search

OR a.asset_id LIKE :search

OR a.asset_tag LIKE :search

OR a.asset_name LIKE :search

)

";

$params['search']="%{$search}%";

}

$sql.="

ORDER BY e.first_name ASC

";

$stmt=$db->prepare($sql);

$stmt->execute($params);

$records=$stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

$totalEmployees=[];

$totalAssignments=0;

foreach($records as $row)
{

$totalEmployees[$row['employee_code']]=true;

$totalAssignments++;

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Employee Asset Report

</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__."/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="p-8">

<div class="flex justify-between items-center mb-8">

<div>

<h1 class="text-4xl font-bold">

Employee Asset Report

</h1>

<p class="text-gray-500">

Assets Assigned to Employees

</p>

</div>

<div class="flex gap-3">

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

🖨 Print

</button>

<a
href="?page=reports"
class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">

← Back

</a>

</div>

</div>

<!-- Dashboard Cards -->

<div class="grid grid-cols-3 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Employees

</p>

<h2 class="text-4xl font-bold text-blue-700 mt-3">

<?= count($totalEmployees) ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Assignments

</p>

<h2 class="text-4xl font-bold text-green-700 mt-3">

<?= $totalAssignments ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Returned

</p>

<h2 class="text-4xl font-bold text-indigo-700 mt-3">

<?= count(array_filter($records,function($r){ return $r['status']=="Returned"; })) ?>

</h2>

</div>

<!-- Search -->

</div>

<div class="bg-white rounded-xl shadow mb-8">

<div class="p-6">

<form method="GET">

<input
type="hidden"
name="page"
value="report-employees">

<div class="flex gap-4">

<input
type="text"
name="search"
value="<?= htmlspecialchars($search) ?>"
placeholder="Employee / Asset ID / Asset Tag..."
class="flex-1 border rounded-lg px-4 py-3">

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

Search

</button>

<a
href="?page=report-employees"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Reset

</a>

</div>

</form>

</div>

</div>

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white">

<tr>

<th class="px-4 py-4 text-left">

Employee

</th>

<th class="px-4 py-4 text-left">

Department

</th>

<th class="px-4 py-4 text-left">

Asset ID

</th>

<th class="px-4 py-4 text-left">

Asset

</th>

<th class="px-4 py-4 text-left">

Assigned

</th>

<th class="px-4 py-4 text-left">

Return

</th>

<th class="px-4 py-4 text-center">

Status

</th>

</tr>

</thead>

<tbody>
    <?php if(count($records) > 0): ?>

<?php foreach($records as $row): ?>

<tr class="border-b hover:bg-blue-50 transition">

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['first_name'].' '.$row['last_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['employee_code']); ?>

</div>

</td>

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['department']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['designation']); ?>

</div>

</td>

<td class="px-4 py-4 font-bold text-blue-700">

<?= htmlspecialchars($row['asset_id']); ?>

</td>

<td class="px-4 py-4">

<div class="font-semibold">

<?= htmlspecialchars($row['asset_name']); ?>

</div>

<div class="text-sm text-gray-500">

<?= htmlspecialchars($row['asset_tag']); ?>

</div>

<div class="text-xs text-gray-400">

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

echo '<span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">' .
htmlspecialchars($row['status']) .
'</span>';

break;

}

?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="7" class="text-center py-16">

<div class="flex flex-col items-center">

<div class="text-6xl mb-4">

👨‍💼

</div>

<h2 class="text-2xl font-bold text-gray-600">

No Employee Asset Records Found

</h2>

<p class="text-gray-500 mt-2">

Try changing the search criteria.

</p>

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

<!-- Export Actions -->

<div class="flex justify-end gap-4 mt-8">

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

🖨 Print

</button>

<a
href="?page=report-export-excel&type=employees"
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

📊 Export Excel

</a>

<a
href="?page=report-export-pdf&type=employees"
class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg">

📄 Export PDF

</a>

</div>

<!-- Summary -->

<div class="bg-white rounded-xl shadow mt-8 p-6">

<h3 class="text-xl font-bold mb-4">

Report Summary

</h3>

<div class="grid grid-cols-3 gap-6">

<div>

<p class="text-gray-500">

Employees with Assets

</p>

<p class="text-3xl font-bold text-blue-700">

<?= count($totalEmployees); ?>

</p>

</div>

<div>

<p class="text-gray-500">

Total Assignments

</p>

<p class="text-3xl font-bold text-green-700">

<?= $totalAssignments; ?>

</p>

</div>

<div>

<p class="text-gray-500">

Report Generated

</p>

<p class="font-semibold">

<?= date('d M Y H:i'); ?>

</p>

</div>

</div>

</div>

<div class="mt-10 text-center text-gray-500 text-sm">

IT Asset Management System © <?= date('Y'); ?>

</div>

</div>

</div>

</div>

</body>

</html>