<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

/*
|--------------------------------------------------------------------------
| Add Category
|--------------------------------------------------------------------------
*/

if($_SERVER['REQUEST_METHOD']=="POST")
{

$stmt=$db->prepare("

INSERT INTO categories
(
category_name,
description,
status
)

VALUES
(
:category_name,
:description,
:status
)

");

$stmt->execute([

'category_name'=>$_POST['category_name'],

'description'=>$_POST['description'],

'status'=>$_POST['status']

]);

header("Location:?page=settings-categories&success=1");

exit;

}

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

$search=$_GET['search'] ?? '';

$sql="

SELECT *

FROM categories

WHERE category_name LIKE :search

ORDER BY category_name

";

$stmt=$db->prepare($sql);

$stmt->execute([

'search'=>"%{$search}%"

]);

$categories=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Asset Categories

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

📂 Asset Categories

</h1>

<p class="text-gray-500">

Manage Asset Categories

</p>

</div>

<a
href="?page=settings"
class="bg-gray-600 text-white px-6 py-3 rounded-lg">

← Back

</a>

</div>

<?php if(isset($_GET['success'])): ?>

<div class="bg-green-100 border border-green-500 text-green-700 p-4 rounded mb-6">

Category Added Successfully

</div>

<?php endif; ?>
<!-- Summary Card -->

<div class="grid grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-6">

        <p class="text-gray-500">

            Total Categories

        </p>

        <h2 class="text-4xl font-bold text-blue-700 mt-3">

            <?= count($categories); ?>

        </h2>

    </div>

</div>

<!-- Search -->

<div class="bg-white rounded-xl shadow mb-8">

    <div class="p-6">

        <form method="GET">

            <input
                type="hidden"
                name="page"
                value="settings-categories">

            <div class="flex gap-4">

                <input
                    type="text"
                    name="search"
                    value="<?= htmlspecialchars($search); ?>"
                    placeholder="Search Category..."
                    class="flex-1 border rounded-lg px-4 py-3">

                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                    Search

                </button>

                <a
                    href="?page=settings-categories"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

                    Reset

                </a>

            </div>

        </form>

    </div>

</div>

<!-- Add Category -->

<div class="bg-white rounded-xl shadow mb-8">

    <div class="border-b px-6 py-4">

        <h2 class="text-2xl font-bold">

            Add New Category

        </h2>

    </div>

    <form method="POST">

        <div class="grid grid-cols-3 gap-6 p-6">

            <div>

                <label class="block mb-2 font-semibold">

                    Category Name

                </label>

                <input
                    type="text"
                    name="category_name"
                    required
                    class="w-full border rounded-lg px-4 py-3">

            </div>

            <div>

                <label class="block mb-2 font-semibold">

                    Description

                </label>

                <input
                    type="text"
                    name="description"
                    class="w-full border rounded-lg px-4 py-3">

            </div>

            <div>

                <label class="block mb-2 font-semibold">

                    Status

                </label>

                <select
                    name="status"
                    class="w-full border rounded-lg px-4 py-3">

                    <option value="Active">

                        Active

                    </option>

                    <option value="Inactive">

                        Inactive

                    </option>

                </select>

            </div>

        </div>

        <div class="px-6 pb-6">

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">

                + Add Category

            </button>

        </div>

    </form>

</div>

<!-- Categories Table -->

<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full">

<thead class="bg-gray-800 text-white">

<tr>

<th class="px-4 py-4 text-left">

#

</th>

<th class="px-4 py-4 text-left">

Category

</th>

<th class="px-4 py-4 text-left">

Description

</th>

<th class="px-4 py-4 text-center">

Status

</th>

<th class="px-4 py-4 text-center">

Action

</th>

</tr>

</thead>

<tbody>

<?php if(count($categories)>0): ?>

<?php foreach($categories as $index=>$category): ?>

<tr class="border-b hover:bg-blue-50">

<td class="px-4 py-4">

<?= $index+1; ?>

</td>

<td class="px-4 py-4 font-semibold">

<?= htmlspecialchars($category['category_name']); ?>

</td>

<td class="px-4 py-4">

<?= htmlspecialchars($category['description']); ?>

</td>

<td class="px-4 py-4 text-center">

<?php if($category['status']=="Active"): ?>

<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

Active

</span>

<?php else: ?>

<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

Inactive

</span>

<?php endif; ?>

</td>

<td class="px-4 py-4 text-center">

<a
href="?page=settings-category-edit&id=<?= $category['id']; ?>"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded">

Edit

</a>

<a
href="?page=settings-category-delete&id=<?= $category['id']; ?>"
onclick="return confirm('Delete this category?')"
class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded ml-2">

Delete

</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="5" class="text-center py-10 text-gray-500">

No Categories Found

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>
</div>

<!-- Information Panel -->

<div class="bg-blue-50 border border-blue-200 rounded-xl mt-8 p-6">

    <h2 class="text-xl font-bold text-blue-800 mb-4">

        📌 Category Management Guide

    </h2>

    <ul class="list-disc list-inside text-gray-700 space-y-2">

        <li>Create categories like Laptop, Desktop, Printer, Router, Monitor.</li>

        <li>Inactive categories won't appear while creating new assets.</li>

        <li>Deleting a category should only be allowed if no asset is using it.</li>

        <li>Use categories to generate accurate inventory reports.</li>

    </ul>

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