
<?php

use App\Controllers\VendorController;

$db = new Database();
$conn = $db->connect();

$vendorController = new VendorController($conn);

$id = $_GET['id'];

$vendor = $vendorController->getById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $vendorController->update(
        $id,
        [
            'vendor_code' => $_POST['vendor_code'],
            'company_name' => $_POST['company_name'],
            'contact_person' => $_POST['contact_person'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'status' => $_POST['status']
        ]
    );

    header('Location: ?page=vendors');
    exit;
}

require __DIR__.'/../layouts/header.php';
require __DIR__.'/../layouts/sidebar.php';
require __DIR__.'/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

<div class="bg-white p-6 rounded shadow">

<h1 class="text-3xl font-bold mb-6">

Edit Vendor

</h1>

<form method="POST">

<div class="mb-4">

<label>Vendor Code</label>

<input
type="text"
name="vendor_code"
value="<?= htmlspecialchars($vendor['vendor_code']) ?>"
class="border p-2 rounded w-full">

</div>

<div class="mb-4">

<label>Company Name</label>

<input
type="text"
name="company_name"
value="<?= htmlspecialchars($vendor['company_name']) ?>"
class="border p-2 rounded w-full">

</div>

<div class="mb-4">

<label>Contact Person</label>

<input
type="text"
name="contact_person"
value="<?= htmlspecialchars($vendor['contact_person']) ?>"
class="border p-2 rounded w-full">

</div>

<div class="mb-4">

<label>Email</label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($vendor['email']) ?>"
class="border p-2 rounded w-full">

</div>

<div class="mb-4">

<label>Phone</label>

<input
type="text"
name="phone"
value="<?= htmlspecialchars($vendor['phone']) ?>"
class="border p-2 rounded w-full">

</div>

<div class="mb-4">

<label>Address</label>

<textarea
name="address"
class="border p-2 rounded w-full"><?= htmlspecialchars($vendor['address']) ?></textarea>

</div>

<div class="mb-4">

<label>Status</label>

<select
name="status"
class="border p-2 rounded w-full">

<option value="Active"
<?= $vendor['status'] == 'Active' ? 'selected' : '' ?>>
Active
</option>

<option value="Inactive"
<?= $vendor['status'] == 'Inactive' ? 'selected' : '' ?>>
Inactive
</option>

</select>

</div>

<button
class="bg-green-600 text-white px-5 py-2 rounded">

Update Vendor

</button>

</form>

</div>

</div>

<?php

require __DIR__.'/../layouts/footer.php';

?>