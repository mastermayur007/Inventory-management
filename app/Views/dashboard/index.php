<?php

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';

?>

<div class="ml-64 p-8 bg-gray-100 min-h-screen">

    <!-- Page Title -->

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Dashboard
            </h1>

            <p class="text-gray-500 mt-2">
                Enterprise IT Asset Management System
            </p>

        </div>

        <div>

            <button class="bg-blue-600 text-white px-5 py-2 rounded">
                Generate Report
            </button>

        </div>

    </div>

    <!-- Statistics Cards -->

    <div class="grid grid-cols-4 gap-6 mb-8">

        <div class="bg-blue-500 text-white rounded shadow p-6">

            <h2 class="text-xl font-bold">
                Departments
            </h2>

            <p class="text-4xl mt-5">
                15
            </p>

        </div>

        <div class="bg-green-500 text-white rounded shadow p-6">

            <h2 class="text-xl font-bold">
                Employees
            </h2>

            <p class="text-4xl mt-5">
                120
            </p>

        </div>

        <div class="bg-yellow-500 text-white rounded shadow p-6">

            <h2 class="text-xl font-bold">
                Vendors
            </h2>

            <p class="text-4xl mt-5">
                32
            </p>

        </div>

        <div class="bg-red-500 text-white rounded shadow p-6">

            <h2 class="text-xl font-bold">
                Assets
            </h2>

            <p class="text-4xl mt-5">
                580
            </p>

        </div>

    </div>

    <!-- Asset Statistics -->

    <div class="grid grid-cols-3 gap-6 mb-8">

        <div class="bg-white rounded shadow p-6">

            <h3 class="font-bold text-lg mb-4">
                Assigned Assets
            </h3>

            <p class="text-4xl text-blue-600">
                420
            </p>

        </div>

        <div class="bg-white rounded shadow p-6">

            <h3 class="font-bold text-lg mb-4">
                Available Assets
            </h3>

            <p class="text-4xl text-green-600">
                130
            </p>

        </div>

        <div class="bg-white rounded shadow p-6">

            <h3 class="font-bold text-lg mb-4">
                Under Repair
            </h3>

            <p class="text-4xl text-red-600">
                30
            </p>

        </div>

    </div>

    <!-- Recent Activities -->

    <div class="bg-white rounded shadow p-6 mb-8">

        <h2 class="text-2xl font-bold mb-6">
            Recent Activities
        </h2>

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-3 border">Activity</th>
                    <th class="p-3 border">User</th>
                    <th class="p-3 border">Date</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td class="border p-3">
                        Laptop Assigned
                    </td>

                    <td class="border p-3">
                        Admin
                    </td>

                    <td class="border p-3">
                        Today
                    </td>

                </tr>

                <tr>

                    <td class="border p-3">
                        Asset Returned
                    </td>

                    <td class="border p-3">
                        Technician
                    </td>

                    <td class="border p-3">
                        Yesterday
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <!-- Quick Actions -->

    <div class="bg-white rounded shadow p-6">

        <h2 class="text-2xl font-bold mb-6">
            Quick Actions
        </h2>

        <div class="flex gap-4">

            <a
                href="?page=department-create"
                class="bg-blue-600 text-white px-5 py-3 rounded">

                Add Department

            </a>

            <a
                href="?page=employee-create"
                class="bg-green-600 text-white px-5 py-3 rounded">

                Add Employee

            </a>

            <a
                href="?page=vendor-create"
                class="bg-yellow-500 text-white px-5 py-3 rounded">

                Add Vendor

            </a>

        </div>

    </div>

</div>

<?php

require __DIR__ . '/../layouts/footer.php';

?>
