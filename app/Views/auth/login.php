<?php
require '../app/Views/layouts/header.php';
?>

<div class="flex justify-center mt-32">

<form
method="POST"
class="bg-white p-8 rounded shadow w-96">

<h1 class="text-2xl mb-5">

Login

</h1>

<input
name="username"
placeholder="Username"
class="border p-2 w-full mb-3">

<input
type="password"
name="password"
placeholder="Password"
class="border p-2 w-full mb-3">

<button
class="bg-blue-600 text-white p-2 w-full">

Login

</button>

</form>

</div>

<?php
require '../app/Views/layouts/footer.php';
?>