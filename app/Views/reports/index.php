<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/

$totalAssets = $db->query("
    SELECT COUNT(*) FROM assets
")->fetchColumn();

$availableAssets = $db->query("
    SELECT COUNT(*) FROM assets
    WHERE status='Available'
")->fetchColumn();

$assignedAssets = $db->query("
    SELECT COUNT(*) FROM assets
    WHERE status='Assigned'
")->fetchColumn();

$repairAssets = $db->query("
    SELECT COUNT(*) FROM assets
    WHERE status='Repair'
")->fetchColumn();

$disposedAssets = $db->query("
    SELECT COUNT(*) FROM assets
    WHERE status='Disposed'
")->fetchColumn();

$totalEmployees = $db->query("
    SELECT COUNT(*) FROM employees
")->fetchColumn();

$totalVendors = $db->query("
    SELECT COUNT(*) FROM vendors
")->fetchColumn();

$totalAssignments = $db->query("
    SELECT COUNT(*) FROM asset_assignments
")->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Reports Dashboard

</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="p-8">

<div class="flex justify-between items-center mb-8">

<div>

<h1 class="text-4xl font-bold text-gray-800">

Reports Dashboard

</h1>

<p class="text-gray-500 mt-2">

Generate and Export IT Asset Reports

</p>

</div>

<div>

<button
onclick="window.print()"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

🖨 Print Report

</button>

</div>

</div>

<!-- Summary Cards -->

<div class="grid grid-cols-4 gap-6">

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Total Assets

</p>

<h2 class="text-4xl font-bold text-blue-700 mt-3">

<?= $totalAssets ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Assigned

</p>

<h2 class="text-4xl font-bold text-red-600 mt-3">

<?= $assignedAssets ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Available

</p>

<h2 class="text-4xl font-bold text-green-600 mt-3">

<?= $availableAssets ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Repair

</p>

<h2 class="text-4xl font-bold text-yellow-600 mt-3">

<?= $repairAssets ?>

</h2>

</div>
</div>

<!-- More Summary Cards -->

<div class="grid grid-cols-4 gap-6 mt-6">

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Disposed

</p>

<h2 class="text-4xl font-bold text-gray-700 mt-3">

<?= $disposedAssets ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Employees

</p>

<h2 class="text-4xl font-bold text-indigo-600 mt-3">

<?= $totalEmployees ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Vendors

</p>

<h2 class="text-4xl font-bold text-purple-600 mt-3">

<?= $totalVendors ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p class="text-gray-500">

Assignments

</p>

<h2 class="text-4xl font-bold text-orange-600 mt-3">

<?= $totalAssignments ?>

</h2>

</div>

</div>

<!-- Reports -->

<div class="bg-white rounded-xl shadow mt-10">

<div class="border-b px-6 py-5">

<h2 class="text-2xl font-bold text-gray-800">

Available Reports

</h2>

<p class="text-gray-500 mt-1">

Generate detailed reports for your IT assets.

</p>

</div>

<div class="grid grid-cols-3 gap-6 p-6">

<!-- Asset Report -->

<a
href="?page=report-assets"
class="border rounded-xl p-6 hover:bg-blue-50 transition">

<div class="text-5xl mb-4">

💻

</div>

<h3 class="text-xl font-bold">

Asset Report

</h3>

<p class="text-gray-500 mt-2">

Complete list of all assets with status and details.

</p>

</a>

<!-- Assignment Report -->

<a
href="?page=report-assignments"
class="border rounded-xl p-6 hover:bg-green-50 transition">

<div class="text-5xl mb-4">

📋

</div>

<h3 class="text-xl font-bold">

Assignment Report

</h3>

<p class="text-gray-500 mt-2">

Assigned and returned assets by employee.

</p>

</a>

<!-- Employee Report -->

<a
href="?page=report-employees"
class="border rounded-xl p-6 hover:bg-indigo-50 transition">

<div class="text-5xl mb-4">

👨‍💼

</div>

<h3 class="text-xl font-bold">

Employee Asset Report

</h3>

<p class="text-gray-500 mt-2">

View assets assigned to each employee.

</p>

</a>

<!-- Vendor Report -->

<a
href="?page=report-vendors"
class="border rounded-xl p-6 hover:bg-purple-50 transition">

<div class="text-5xl mb-4">

🏢

</div>

<h3 class="text-xl font-bold">

Vendor Report

</h3>

<p class="text-gray-500 mt-2">

Assets grouped by vendor.

</p>

</a>

<!-- Warranty Report -->

<a
href="?page=report-warranty"
class="border rounded-xl p-6 hover:bg-yellow-50 transition">

<div class="text-5xl mb-4">

🛡️

</div>

<h3 class="text-xl font-bold">

Warranty Report

</h3>

<p class="text-gray-500 mt-2">

Expired and upcoming warranty information.

</p>

</a>

<!-- Purchase Report -->

<a
href="?page=report-purchase"
class="border rounded-xl p-6 hover:bg-red-50 transition">

<div class="text-5xl mb-4">

🛒

</div>

<h3 class="text-xl font-bold">

Purchase Report

</h3>

<p class="text-gray-500 mt-2">

Assets purchased by date range.

</p>

</a>

</div>

</div>
<!-- Export Section -->

<div class="bg-white rounded-xl shadow mt-10">

    <div class="border-b px-6 py-5">

        <h2 class="text-2xl font-bold text-gray-800">

            Export Reports

        </h2>

        <p class="text-gray-500 mt-1">

            Download reports in different formats.

        </p>

    </div>

    <div class="grid grid-cols-3 gap-6 p-6">

        <!-- PDF -->

        <a
            href="?page=report-export-pdf"
            class="bg-red-600 hover:bg-red-700 text-white rounded-xl p-6 text-center transition">

            <div class="text-5xl mb-3">

                📄

            </div>

            <h3 class="text-xl font-bold">

                Export PDF

            </h3>

            <p class="mt-2 text-red-100">

                Download printable PDF report

            </p>

        </a>

        <!-- Excel -->

        <a
            href="?page=report-export-excel"
            class="bg-green-600 hover:bg-green-700 text-white rounded-xl p-6 text-center transition">

            <div class="text-5xl mb-3">

                📊

            </div>

            <h3 class="text-xl font-bold">

                Export Excel

            </h3>

            <p class="mt-2 text-green-100">

                Download Microsoft Excel report

            </p>

        </a>

        <!-- Print -->

        <button
            onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-6 transition">

            <div class="text-5xl mb-3">

                🖨️

            </div>

            <h3 class="text-xl font-bold">

                Print Report

            </h3>

            <p class="mt-2 text-blue-100">

                Print the current dashboard

            </p>

        </button>

    </div>

</div>

<!-- Recent Activity -->

<div class="bg-white rounded-xl shadow mt-10">

    <div class="border-b px-6 py-5">

        <h2 class="text-2xl font-bold text-gray-800">

            Recent Activity

        </h2>

    </div>

    <div class="p-6">

        <div class="grid grid-cols-2 gap-6">

            <div class="border rounded-lg p-5">

                <h3 class="font-bold text-lg mb-4">

                    Latest Assets

                </h3>

                <p class="text-gray-500">

                    Coming Soon...

                </p>

            </div>

            <div class="border rounded-lg p-5">

                <h3 class="font-bold text-lg mb-4">

                    Latest Assignments

                </h3>

                <p class="text-gray-500">

                    Coming Soon...

                </p>

            </div>

        </div>

    </div>

</div>

<!-- Footer -->

<div class="mt-10 text-center text-gray-500 text-sm">

    IT Asset Management System © <?= date('Y'); ?>

</div>

</div>

</div>

</div>

</body>

</html>