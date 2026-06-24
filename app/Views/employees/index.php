<?php

use App\Controllers\EmployeeController;

$db = new Database();
$conn = $db->connect();

$employeeController = new EmployeeController($conn);

$employees = $employeeController->index();

require __DIR__.'/../layouts/header.php';
require __DIR__.'/../layouts/sidebar.php';
require __DIR__.'/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

    <div class="bg-white p-6 rounded shadow">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-3xl font-bold">

                Employees

            </h1>

            <a
                href="?page=employee-create"
                class="bg-blue-600 text-white px-4 py-2 rounded">

                Add Employee

            </a>

        </div>

        <table class="w-full border border-gray-300">

            <thead class="bg-gray-200">

                <tr>

                    <th class="border p-3">Code</th>

                    <th class="border p-3">Department</th>

                    <th class="border p-3">Employee Name</th>

                    <th class="border p-3">Designation</th>

                    <th class="border p-3">Email</th>

                    <th class="border p-3">Mobile</th>

                    <th class="border p-3">Status</th>

                    <th class="border p-3">Action</th>

                </tr>

            </thead>

            <tbody>

            <?php foreach($employees as $employee): ?>

                <tr>

                    <td class="border p-3">

                        <?= $employee['employee_code'] ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($employee['department_name']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars(
                            $employee['first_name'].' '.$employee['last_name']
                        ) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($employee['designation']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($employee['email']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($employee['mobile']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($employee['status']) ?>

                    </td>

                    <td class="border p-3">

                        <a
                        href="?page=employee-edit&id=<?= $employee['id'] ?>"
                        class="text-blue-600">

                        Edit

                        </a>

                        |

                        <a
                        href="?page=employee-delete&id=<?= $employee['id'] ?>"
                        class="text-red-600">

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

require __DIR__.'/../layouts/footer.php';

?>