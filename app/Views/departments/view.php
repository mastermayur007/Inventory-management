<?php

use App\Controllers\DepartmentController;

$db = new Database();
$conn = $db->connect();

$departmentController = new DepartmentController($conn);

$departments = $departmentController->index();

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

    <div class="bg-white p-6 rounded shadow">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-3xl font-bold">
                Departments
            </h1>

            <a
                href="?page=department-create"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">

                Add Department

            </a>

        </div>

        <table class="w-full border border-gray-300">

            <thead class="bg-gray-200">

                <tr>

                    <th class="border p-3">ID</th>

                    <th class="border p-3">Department Name</th>

                    <th class="border p-3">Department Code</th>

                    <th class="border p-3">Description</th>

                    <th class="border p-3">Status</th>

                    <th class="border p-3">Action</th>

                </tr>

            </thead>

            <tbody>

            <?php foreach ($departments as $department): ?>

                <tr>

                    <td class="border p-3">
                        <?= $department['id'] ?>
                    </td>

                    <td class="border p-3">
                        <?= htmlspecialchars($department['department_name']) ?>
                    </td>

                    <td class="border p-3">
                        <?= htmlspecialchars($department['department_code']) ?>
                    </td>

                    <td class="border p-3">
                        <?= htmlspecialchars($department['description']) ?>
                    </td>

                    <td class="border p-3">
                        <?= htmlspecialchars($department['status']) ?>
                    </td>

                    <td class="border p-3">

                        <a
                            href="?page=department-edit&id=<?= $department['id'] ?>"
                            class="text-blue-600">

                            Edit

                        </a>

                        |

                       <a
href="http://localhost/itam/public/?page=department-delete&id=<?= $department['id'] ?>"
class="text-red-600"
onclick="return confirm('Are you sure you want to delete this department?');">

Delete

</a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php

require __DIR__ . '/../layouts/footer.php';

?>