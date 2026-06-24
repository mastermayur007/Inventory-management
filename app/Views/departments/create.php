<?php

use App\Controllers\DepartmentController;

$db = new Database();
$conn = $db->connect();

$departmentController = new DepartmentController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $departmentController->store([
        'department_name' => $_POST['department_name'],
        'department_code' => $_POST['department_code'],
        'description' => $_POST['description'],
        'status' => 'Active'
    ]);

    header('Location: ?page=departments');
    exit;
}

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

<div class="bg-white p-6 rounded shadow">

<h1 class="text-3xl font-bold mb-6">

Add Department

</h1>

<form method="POST">

<div class="mb-4">

<label>Department Name</label>

<input
type="text"
name="department_name"
class="border p-2 rounded w-full"
required>

</div>

<div class="mb-4">

<label>Department Code</label>

<input
type="text"
name="department_code"
class="border p-2 rounded w-full"
required>

</div>

<div class="mb-4">

<label>Description</label>

<textarea
name="description"
class="border p-2 rounded w-full"></textarea>

</div>

<button
type="submit"
class="bg-blue-600 text-white px-4 py-2 rounded">

Save

</button>

</form>

</div>

</div>

<?php

require __DIR__ . '/../layouts/footer.php';

?>