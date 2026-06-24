
<?php

use App\Controllers\VendorController;

$db = new Database();
$conn = $db->connect();

$vendorController = new VendorController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $vendorController->store([

        'vendor_code' => $_POST['vendor_code'],
        'company_name' => $_POST['company_name'],
        'contact_person' => $_POST['contact_person'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'status' => $_POST['status']

    ]);

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

            Add Vendor

        </h1>

        <form method="POST">

            <div class="mb-4">

                <label class="block mb-2">

                    Vendor Code

                </label>

                <input
                    type="text"
                    name="vendor_code"
                    class="border p-2 rounded w-full"
                    required>

            </div>

            <div class="mb-4">

                <label class="block mb-2">

                    Company Name

                </label>

                <input
                    type="text"
                    name="company_name"
                    class="border p-2 rounded w-full"
                    required>

            </div>

            <div class="mb-4">

                <label class="block mb-2">

                    Contact Person

                </label>

                <input
                    type="text"
                    name="contact_person"
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">

                    Email

                </label>

                <input
                    type="email"
                    name="email"
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">

                    Phone

                </label>

                <input
                    type="text"
                    name="phone"
                    class="border p-2 rounded w-full">

            </div>

            <div class="mb-4">

                <label class="block mb-2">

                    Address

                </label>

                <textarea
                    name="address"
                    class="border p-2 rounded w-full"
                    rows="3"></textarea>

            </div>

            <div class="mb-4">

                <label class="block mb-2">

                    Status

                </label>

                <select
                    name="status"
                    class="border p-2 rounded w-full">

                    <option value="Active">

                        Active

                    </option>

                    <option value="Inactive">

                        Inactive

                    </option>

                </select>

            </div>

            <button
                type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded">

                Save Vendor

            </button>

        </form>

    </div>

</div>

<?php

require __DIR__.'/../layouts/footer.php';

?>