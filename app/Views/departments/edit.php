<?php

use App\Controllers\DepartmentController;

$db = new Database();
$conn = $db->connect();

$departmentController = new DepartmentController($conn);

// Validate ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ?page=departments');
    exit;
}

$id = (int) $_GET['id'];

$department = $departmentController->getById($id);

// Department not found
if (!$department) {
    header('Location: ?page=departments');
    exit;
}

// Update Department
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $departmentController->update(
        $id,
        [
            'department_name' => $_POST['department_name'],
            'department_code' => $_POST['department_code'],
            'description' => $_POST['description']
        ]
    );

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
            Edit Department
        </h1>

        <form method="POST">

            <div class="mb-4">

                <label class="block mb-2">
                    Department Name
                </label>

                <input
                    type="text"
                    name="department_name"
                    value="<?= htmlspecialchars($department['department_name']) ?>"
                    class="border p-2 rounded w-full"
                    required
                >

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Department Code
                </label>

                <input
                    type="text"
                    name="department_code"
                    value="<?= htmlspecialchars($department['department_code']) ?>"
                    class="border p-2 rounded w-full"
                    required
                >

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    class="border p-2 rounded w-full"
                    rows="4"
                ><?= htmlspecialchars($department['description']) ?></textarea>

            </div>

            <button
                type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            >
                Update
            </button>

            <a
                href="?page=departments"
                class="bg-gray-500 text-white px-4 py-2 rounded ml-2"
            >
                Cancel
            </a>

        </form>

    </div>

</div>

<?php

require __DIR__ . '/../layouts/footer.php';

?>