<?php

require_once __DIR__."/../../../config/database.php";

$db=(new Database())->connect();

$company=$db->query("
SELECT *
FROM company_settings
LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD']=="POST")
{

$sql="

UPDATE company_settings

SET

company_name=:company_name,

company_email=:company_email,

company_phone=:company_phone,

company_website=:company_website,

address=:address,

city=:city,

state=:state,

country=:country,

postal_code=:postal_code

WHERE id=1

";

$stmt=$db->prepare($sql);

$stmt->execute([

'company_name'=>$_POST['company_name'],

'company_email'=>$_POST['company_email'],

'company_phone'=>$_POST['company_phone'],

'company_website'=>$_POST['company_website'],

'address'=>$_POST['address'],

'city'=>$_POST['city'],

'state'=>$_POST['state'],

'country'=>$_POST['country'],

'postal_code'=>$_POST['postal_code']

]);

header("Location:?page=settings-company&success=1");

exit;

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Company Settings

</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<?php require_once __DIR__."/../layouts/sidebar.php"; ?>

<div class="flex-1 ml-64">

<div class="p-8">

<div class="flex justify-between">

<div>

<h1 class="text-4xl font-bold">

🏢 Company Information

</h1>

<p class="text-gray-500">

Manage organization details

</p>

</div>

<a
href="?page=settings"
class="bg-gray-600 text-white px-6 py-3 rounded-lg">

← Back

</a>

</div>

<?php if(isset($_GET['success'])): ?>

<div class="bg-green-100 border border-green-500 text-green-700 p-4 rounded-lg mt-6">

Company information updated successfully.

</div>

<?php endif; ?>

<div class="bg-white rounded-xl shadow mt-8">

<form method="POST">

<div class="grid grid-cols-2 gap-6 p-8">
    <!-- Company Name -->

<div>

<label class="block mb-2 font-semibold">

Company Name

</label>

<input
type="text"
name="company_name"
required
value="<?= htmlspecialchars($company['company_name']); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- Email -->

<div>

<label class="block mb-2 font-semibold">

Company Email

</label>

<input
type="email"
name="company_email"
value="<?= htmlspecialchars($company['company_email']); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- Phone -->

<div>

<label class="block mb-2 font-semibold">

Phone Number

</label>

<input
type="text"
name="company_phone"
value="<?= htmlspecialchars($company['company_phone']); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- Website -->

<div>

<label class="block mb-2 font-semibold">

Website

</label>

<input
type="text"
name="company_website"
value="<?= htmlspecialchars($company['company_website']); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- Address -->

<div class="col-span-2">

<label class="block mb-2 font-semibold">

Address

</label>

<textarea
name="address"
rows="3"
class="w-full border rounded-lg px-4 py-3"><?= htmlspecialchars($company['address']); ?></textarea>

</div>

<!-- City -->

<div>

<label class="block mb-2 font-semibold">

City

</label>

<input
type="text"
name="city"
value="<?= htmlspecialchars($company['city']); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- State -->

<div>

<label class="block mb-2 font-semibold">

State

</label>

<input
type="text"
name="state"
value="<?= htmlspecialchars($company['state']); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- Country -->

<div>

<label class="block mb-2 font-semibold">

Country

</label>

<input
type="text"
name="country"
value="<?= htmlspecialchars($company['country']); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- Postal Code -->

<div>

<label class="block mb-2 font-semibold">

Postal Code

</label>

<input
type="text"
name="postal_code"
value="<?= htmlspecialchars($company['postal_code']); ?>"
class="w-full border rounded-lg px-4 py-3">

</div>

<!-- Logo Upload (UI only for now) -->

<div class="col-span-2">

<label class="block mb-2 font-semibold">

Company Logo

</label>

<input
type="file"
name="logo"
class="w-full border rounded-lg px-4 py-3">

<p class="text-sm text-gray-500 mt-2">

Logo upload functionality will be added in a later step.

</p>

</div>
<div class="col-span-2 flex justify-end gap-4 mt-6">

<a
href="?page=settings"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg">

Cancel

</a>

<button
type="submit"
class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg">

💾 Save Changes

</button>

</div>

</div>

</form>

</div>

<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">

<h3 class="text-xl font-bold text-blue-800 mb-3">

ℹ Company Information Usage

</h3>

<ul class="list-disc list-inside text-gray-700 space-y-2">

<li>Displayed on the dashboard.</li>

<li>Printed on PDF reports.</li>

<li>Shown in exported documents.</li>

<li>Used in email notifications.</li>

<li>Displayed in the application header (optional).</li>

</ul>

</div>

<div class="mt-10 text-center text-gray-500 text-sm">

IT Asset Management System © <?= date('Y'); ?>

</div>

</div>

</div>

</div>

</body>

</html>