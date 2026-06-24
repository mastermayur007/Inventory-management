<?php

use App\Controllers\EmployeeController;
use App\Models\Department;

$db = new Database();
$conn = $db->connect();

$employeeController = new EmployeeController($conn);

$departmentModel = new Department($conn);

$departments = $departmentModel->getDropdown();

$id = $_GET['id'];

$employee = $employeeController->getById($id);

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $employeeController->update($id,[

        'employee_code'=>$_POST['employee_code'],
        'department_id'=>$_POST['department_id'],
        'first_name'=>$_POST['first_name'],
        'last_name'=>$_POST['last_name'],
        'designation'=>$_POST['designation'],
        'email'=>$_POST['email'],
        'mobile'=>$_POST['mobile'],
        'status'=>$_POST['status']

    ]);

    header('Location: ?page=employees');

    exit;
}

require __DIR__.'/../layouts/header.php';
require __DIR__.'/../layouts/sidebar.php';
require __DIR__.'/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

<div class="bg-white p-6 rounded shadow">

<h1 class="text-3xl font-bold mb-6">

Edit Employee

</h1>

<form method="POST">

<input
type="text"
name="employee_code"
value="<?= $employee['employee_code'] ?>"
class="border p-2 rounded w-full mb-4">

<input
type="text"
name="first_name"
value="<?= $employee['first_name'] ?>"
class="border p-2 rounded w-full mb-4">

<input
type="text"
name="last_name"
value="<?= $employee['last_name'] ?>"
class="border p-2 rounded w-full mb-4">

<input
type="text"
name="designation"
value="<?= $employee['designation'] ?>"
class="border p-2 rounded w-full mb-4">

<input
type="email"
name="email"
value="<?= $employee['email'] ?>"
class="border p-2 rounded w-full mb-4">

<input
type="text"
name="mobile"
value="<?= $employee['mobile'] ?>"
class="border p-2 rounded w-full mb-4">

<button
class="bg-green-600 text-white px-5 py-2 rounded">

Update Employee

</button>

</form>

</div>

</div>

<?php

require __DIR__.'/../layouts/footer.php';

?>

