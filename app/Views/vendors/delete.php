
<?php

use App\Controllers\VendorController;

$db = new Database();
$conn = $db->connect();

$vendorController = new VendorController($conn);

$id = $_GET['id'];

$vendor = $vendorController->getById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['confirm_delete']))
    {
        $vendorController->delete($id);

        header('Location: ?page=vendors');
        exit;
    }

    if (isset($_POST['cancel']))
    {
        header('Location: ?page=vendors');
        exit;
    }
}

require __DIR__.'/../layouts/header.php';
require __DIR__.'/../layouts/sidebar.php';
require __DIR__.'/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">

        <h1 class="text-3xl font-bold text-red-600 mb-6">

            Delete Vendor

        </h1>

        <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded mb-6">

            Are you sure you want to delete this vendor?

        </div>

        <table class="w-full border mb-8">

            <tr>
                <th class="border p-3 bg-gray-100">Vendor Code</th>
                <td class="border p-3">
                    <?= htmlspecialchars($vendor['vendor_code']) ?>
                </td>
            </tr>

            <tr>
                <th class="border p-3 bg-gray-100">Company Name</th>
                <td class="border p-3">
                    <?= htmlspecialchars($vendor['company_name']) ?>
                </td>
            </tr>

            <tr>
                <th class="border p-3 bg-gray-100">Contact Person</th>
                <td class="border p-3">
                    <?= htmlspecialchars($vendor['contact_person']) ?>
                </td>
            </tr>

            <tr>
                <th class="border p-3 bg-gray-100">Email</th>
                <td class="border p-3">
                    <?= htmlspecialchars($vendor['email']) ?>
                </td>
            </tr>

        </table>

        <form method="POST">

            <div class="flex gap-4">

                <button
                    type="submit"
                    name="confirm_delete"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded">

                    Delete Vendor

                </button>

                <button
                    type="submit"
                    name="cancel"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded">

                    Cancel

                </button>

            </div>

        </form>

    </div>

</div>

<?php

require __DIR__.'/../layouts/footer.php';

?>
