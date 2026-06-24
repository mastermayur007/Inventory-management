<?php

use App\Controllers\AssetController;

$db = new Database();
$conn = $db->connect();

$assetController = new AssetController($conn);

$id = $_GET['id'];

$asset = $assetController->getById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['confirm_delete']))
    {
        $assetController->delete($id);

        header('Location: ?page=assets');
        exit;
    }

    if (isset($_POST['cancel']))
    {
        header('Location: ?page=assets');
        exit;
    }
}

require __DIR__.'/../layouts/header.php';
require __DIR__.'/../layouts/sidebar.php';
require __DIR__.'/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">

<h1 class="text-3xl font-bold text-red-600 mb-6">

⚠ Delete Asset

</h1>

<p class="mb-6">

Are you sure you want to delete this asset?

</p>

<div class="border rounded p-4 mb-6">

<b>Asset Tag :</b>

<?= htmlspecialchars($asset['asset_tag']) ?>

<br><br>

<b>Asset Name :</b>

<?= htmlspecialchars($asset['asset_name']) ?>

<br><br>

<b>Brand :</b>

<?= htmlspecialchars($asset['brand']) ?>

<br><br>

<b>Status :</b>

<?= htmlspecialchars($asset['status']) ?>

</div>

<form method="POST">

<button
name="confirm_delete"
class="bg-red-600 text-white px-6 py-3 rounded">

Delete Asset

</button>

<button
name="cancel"
class="bg-gray-500 text-white px-6 py-3 rounded ml-3">

Cancel

</button>

</form>

</div>

</div>

<?php require __DIR__.'/../layouts/footer.php'; ?>
