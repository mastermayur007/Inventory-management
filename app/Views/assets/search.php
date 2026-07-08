<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$search = trim($_GET['search'] ?? '');

if($search=="")
{
    header("Location:?page=dashboard");
    exit;
}
$sql="

SELECT *

FROM assets

WHERE

asset_tag LIKE :search

OR

serial_number LIKE :search

LIMIT 20

";

$stmt=$db->prepare($sql);

$stmt->execute([

'search'=>"%{$search}%"

]);

$assets=$stmt->fetchAll(PDO::FETCH_ASSOC);
if(count($assets)==1)
{

header(

"Location:?page=asset-show&id=".$assets[0]['id']

);

exit;

}
if(count($assets)==0)
{

echo "

<h2 style='text-align:center;
margin-top:100px;
font-size:35px;
color:red;'>

No Asset Found

</h2>

";

exit;

}
?>

<!DOCTYPE html>

<html>

<head>

<title>

Search Results

</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-10">

<h1 class="text-3xl font-bold mb-8">

Search Results

</h1>

<table
class="min-w-full bg-white rounded shadow">

<thead>

<tr class="bg-gray-200">

<th class="p-4">

Asset Tag

</th>

<th class="p-4">

Asset Name

</th>

<th class="p-4">

Brand

</th>

<th class="p-4">

Model

</th>

<th class="p-4">

Action

</th>

</tr>

</thead>

<tbody>

<?php

foreach($assets as $asset):

?>

<tr>

<td class="border p-3">

<?= $asset['asset_tag']; ?>

</td>

<td class="border p-3">

<?= $asset['asset_name']; ?>

</td>

<td class="border p-3">

<?= $asset['brand']; ?>

</td>

<td class="border p-3">

<?= $asset['model']; ?>

</td>

<td class="border p-3">

<a

href="?page=asset-show&id=<?= $asset['id']; ?>"

class="bg-blue-600 text-white px-4 py-2 rounded">

View

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</body>

</html>