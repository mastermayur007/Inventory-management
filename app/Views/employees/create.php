<?php

use App\Controllers\EmployeeController;
use App\Models\Department;

$db = new Database();
$conn = $db->connect();

$employeeController = new EmployeeController($conn);

$departmentModel = new Department($conn);

$departments = $departmentModel->getDropdown();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $employeeController->store([

        'employee_code' => $_POST['employee_code'],
        'department_id' => $_POST['department_id'],
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'designation' => $_POST['designation'],
        'email' => $_POST['email'],
        'mobile' => $_POST['mobile'],
        'status' => $_POST['status']

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

Add Employee

</h1>

<form method="POST">

<div class="grid grid-cols-2 gap-6">

<div>

<label>Employee Code</label>

<input
type="text"
name="employee_code"
class="border p-2 rounded w-full"
required>

</div>

<div>

<label>Department</label>

<select
name="department_id"
class="border p-2 rounded w-full">

<?php foreach($departments as $department): ?>

<option value="<?= $department['id'] ?>">

<?= htmlspecialchars($department['department_name']) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div>

<label>First Name</label>

<input
type="text"
name="first_name"
class="border p-2 rounded w-full">

</div>

<div>

<label>Last Name</label>

<input
type="text"
name="last_name"
class="border p-2 rounded w-full">

</div>

<div>

<label>Designation</label>

<input
type="text"
name="designation"
class="border p-2 rounded w-full">

</div>

<div>

<label>Email</label>

<input
type="email"
name="email"
class="border p-2 rounded w-full">

</div>

<div>

<label>Mobile</label>

<input
type="text"
name="mobile"
class="border p-2 rounded w-full">

</div>

<div>

<label>Status</label>

<select
name="status"
class="border p-2 rounded w-full">

<option value="Active">

Active

</option>

<option value="Inactive">

Inactive

</option>

</select>

</div>

</div>

<button
class="bg-blue-600 text-white px-5 py-2 rounded mt-6">

Save Employee

</button>

</form>

</div>

</div>

<?php

require __DIR__.'/../layouts/footer.php';

?>