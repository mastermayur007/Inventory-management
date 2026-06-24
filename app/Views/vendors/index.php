
<?php

use App\Controllers\VendorController;

$db = new Database();
$conn = $db->connect();

$vendorController = new VendorController($conn);

$vendors = $vendorController->index();

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';

?>

<div class="ml-64 p-8">

    <div class="bg-white p-6 rounded shadow">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-3xl font-bold">

                Vendors

            </h1>

            <a
                href="?page=vendor-create"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">

                Add Vendor

            </a>

        </div>

        <table class="w-full border border-gray-300">

            <thead class="bg-gray-200">

                <tr>

                    <th class="border p-3">Vendor Code</th>

                    <th class="border p-3">Company Name</th>

                    <th class="border p-3">Contact Person</th>

                    <th class="border p-3">Email</th>

                    <th class="border p-3">Phone</th>

                    <th class="border p-3">Address</th>

                    <th class="border p-3">Status</th>

                    <th class="border p-3">Action</th>

                </tr>

            </thead>

            <tbody>

            <?php foreach ($vendors as $vendor): ?>

                <tr>

                    <td class="border p-3">

                        <?= htmlspecialchars($vendor['vendor_code']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($vendor['company_name']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($vendor['contact_person']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($vendor['email']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($vendor['phone']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($vendor['address']) ?>

                    </td>

                    <td class="border p-3">

                        <?= htmlspecialchars($vendor['status']) ?>

                    </td>

                    <td class="border p-3">

                        <a
                            href="?page=vendor-edit&id=<?= $vendor['id'] ?>"
                            class="text-blue-600">

                            Edit

                        </a>

                        |

                        <a
                            href="?page=vendor-delete&id=<?= $vendor['id'] ?>"
                            class="text-red-600 hover:text-red-800 font-semibold">

                            Delete

                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

<?php

require __DIR__ . '/../layouts/footer.php';

?>
