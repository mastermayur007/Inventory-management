<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$totalCategories = $db->query("
SELECT COUNT(DISTINCT category)
FROM assets
")->fetchColumn();

$totalLocations = $db->query("
SELECT COUNT(*)
FROM departments
")->fetchColumn();

$totalVendors = $db->query("
SELECT COUNT(*)
FROM vendors
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

System Settings

</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__."/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="p-8">

<div class="flex justify-between items-center mb-8">

<div>

<h1 class="text-4xl font-bold">

⚙ System Settings

</h1>

<p class="text-gray-500">

Configure your IT Asset Management System

</p>

</div>

</div>

<!-- Summary -->

<div class="grid grid-cols-3 gap-6 mb-8">

<div class="bg-white rounded-xl shadow p-6">

<p>Categories</p>

<h2 class="text-4xl font-bold text-blue-700">

<?= $totalCategories ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p>Departments</p>

<h2 class="text-4xl font-bold text-green-700">

<?= $totalLocations ?>

</h2>

</div>

<div class="bg-white rounded-xl shadow p-6">

<p>Vendors</p>

<h2 class="text-4xl font-bold text-purple-700">

<?= $totalVendors ?>

</h2>

</div>

</div>

<!-- Settings Modules -->

<div class="grid grid-cols-3 gap-6">
<!-- Company -->

<a
href="?page=settings-company"
class="bg-white rounded-xl shadow p-6 hover:bg-blue-50">

<div class="text-5xl mb-4">🏢</div>

<h2 class="text-xl font-bold">

Company Information

</h2>

<p class="text-gray-500 mt-2">

Manage company name, logo and address.

</p>

</a>

<!-- Profile -->

<a
href="?page=settings-profile"
class="bg-white rounded-xl shadow p-6 hover:bg-green-50">

<div class="text-5xl mb-4">👤</div>

<h2 class="text-xl font-bold">

User Profile

</h2>

<p class="text-gray-500 mt-2">

Update profile and password.

</p>

</a>

<!-- Categories -->

<a
href="?page=settings-categories"
class="bg-white rounded-xl shadow p-6 hover:bg-yellow-50">

<div class="text-5xl mb-4">📂</div>

<h2 class="text-xl font-bold">

Asset Categories

</h2>

<p class="text-gray-500 mt-2">

Laptop, Desktop, Printer...

</p>

</a>

<!-- Locations -->

<a
href="?page=settings-locations"
class="bg-white rounded-xl shadow p-6 hover:bg-indigo-50">

<div class="text-5xl mb-4">📍</div>

<h2 class="text-xl font-bold">

Office Locations

</h2>

<p class="text-gray-500 mt-2">

Manage company locations.

</p>

</a>

<!-- Manufacturers -->

<a
href="?page=settings-manufacturers"
class="bg-white rounded-xl shadow p-6 hover:bg-purple-50">

<div class="text-5xl mb-4">🏭</div>

<h2 class="text-xl font-bold">

Manufacturers

</h2>

<p class="text-gray-500 mt-2">

Dell, HP, Lenovo, Cisco...

</p>

</a>

<!-- Asset Status -->

<a
href="?page=settings-status"
class="bg-white rounded-xl shadow p-6 hover:bg-red-50">

<div class="text-5xl mb-4">📊</div>

<h2 class="text-xl font-bold">

Asset Status

</h2>

<p class="text-gray-500 mt-2">

Available, Assigned, Repair...

</p>

</a>
<!-- Email -->

<a
href="?page=settings-email"
class="bg-white rounded-xl shadow p-6 hover:bg-orange-50">

<div class="text-5xl mb-4">📧</div>

<h2 class="text-xl font-bold">

Email Settings

</h2>

<p class="text-gray-500 mt-2">

SMTP configuration.

</p>

</a>

<!-- Backup -->

<a
href="?page=settings-backup"
class="bg-white rounded-xl shadow p-6 hover:bg-gray-50">

<div class="text-5xl mb-4">💾</div>

<h2 class="text-xl font-bold">

Backup & Restore

</h2>

<p class="text-gray-500 mt-2">

Backup database and restore.

</p>

</a>

<!-- Security -->

<a
href="?page=settings-security"
class="bg-white rounded-xl shadow p-6 hover:bg-red-100">

<div class="text-5xl mb-4">🔒</div>

<h2 class="text-xl font-bold">

Security

</h2>

<p class="text-gray-500 mt-2">

Roles, permissions and passwords.

</p>

</a>

</div>

<div class="mt-10 text-center text-gray-500 text-sm">

IT Asset Management System © <?= date('Y'); ?>

</div>

</div>

</div>

</div>

</body>

</html>        