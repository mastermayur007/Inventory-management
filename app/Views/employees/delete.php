```php
<?php

use App\Controllers\EmployeeController;

$db = new Database();
$conn = $db->connect();

$employeeController = new EmployeeController($conn);

$id = $_GET['id'];

$employee = $employeeController->getById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['confirm_delete']))
    {
        $employeeController->delete($id);

        header('Location: ?page=employees');
        exit;
    }

    if (isset($_POST['cancel']))
    {
        header('Location: ?page=employees');
        exit;
    }
}

require __DIR__.'/../layouts/header.php';
require __DIR__.'/../layouts/sidebar.php';
require __DIR__.'/../layouts/navbar.php';
?>

<div class="ml-64 p-8">

<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">

<h1 class="text-3xl font-bold text-red-600 mb-6">

⚠ Delete Employee

</h1>

<p class="mb-6">

Are you sure you want to delete this employee?

</p>

<div class="border rounded p-4 mb-6">

<b>Employee Code :</b>
<?= htmlspecialchars($employee['employee_code']) ?>

<br><br>

<b>Name :</b>
<?= htmlspecialchars($employee['first_name']) ?>
<?= htmlspecialchars($employee['last_name']) ?>

</div>

<form method="POST">

<button
name="confirm_delete"
class="bg-red-600 text-white px-6 py-3 rounded">

Delete Employee

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
```
