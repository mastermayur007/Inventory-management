<?php

require_once __DIR__ . "/../../../config/database.php";

$db = (new Database())->connect();

$id = $_GET['id'] ?? 0;

if (!$id) {
    header("Location:?page=settings-categories");
    exit;
}

/*
|--------------------------------------------------------------------------
| Fetch Category
|--------------------------------------------------------------------------
*/

$stmt = $db->prepare("
SELECT *
FROM categories
WHERE id=:id
LIMIT 1
");

$stmt->execute([
    'id'=>$id
]);

$category = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$category){
    die("Category not found.");
}

/*
|--------------------------------------------------------------------------
| Update
|--------------------------------------------------------------------------
*/

if($_SERVER['REQUEST_METHOD']=="POST")
{

    $name = trim($_POST['category_name']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    // Duplicate check

    $check = $db->prepare("
    SELECT id
    FROM categories
    WHERE category_name=:name
    AND id!=:id
    ");

    $check->execute([
        'name'=>$name,
        'id'=>$id
    ]);

    if($check->fetch()){

        $error = "Category already exists.";

    }else{

        $update = $db->prepare("
        UPDATE categories
        SET
            category_name=:name,
            description=:description,
            status=:status
        WHERE id=:id
        ");

        $update->execute([

            'name'=>$name,
            'description'=>$description,
            'status'=>$status,
            'id'=>$id

        ]);

        header("Location:?page=settings-categories&updated=1");
        exit;
    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Edit Category</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex">

<?php require_once __DIR__.'/../layouts/sidebar.php'; ?>

<div class="flex-1 ml-64 p-8">

<div class="bg-white rounded-xl shadow">

<div class="border-b p-6">

<h1 class="text-3xl font-bold">

Edit Category

</h1>

</div>

<?php if(isset($error)): ?>

<div class="m-6 bg-red-100 border border-red-500 text-red-700 p-4 rounded">

<?= $error ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="grid grid-cols-2 gap-6 p-6">

<div>

<label class="font-semibold">

Category Name

</label>

<input
type="text"
name="category_name"
required
value="<?= htmlspecialchars($category['category_name']) ?>"
class="w-full border rounded-lg p-3 mt-2">

</div>

<div>

<label class="font-semibold">

Status

</label>

<select
name="status"
class="w-full border rounded-lg p-3 mt-2">

<option
value="Active"
<?= $category['status']=="Active"?"selected":"" ?>>

Active

</option>

<option
value="Inactive"
<?= $category['status']=="Inactive"?"selected":"" ?>>

Inactive

</option>

</select>

</div>

<div class="col-span-2">

<label class="font-semibold">

Description

</label>

<textarea
name="description"
rows="4"
class="w-full border rounded-lg p-3 mt-2"><?= htmlspecialchars($category['description']) ?></textarea>

</div>

</div>

<div class="border-t p-6 flex justify-end gap-3">

<a
href="?page=settings-categories"
class="bg-gray-500 text-white px-5 py-3 rounded">

Cancel

</a>

<button
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded">

Update Category

</button>

</div>

</form>

</div>

</div>

</div>

</body>

</html>