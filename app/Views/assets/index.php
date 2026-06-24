
<?php

use App\Controllers\AssetController;

$db = new Database();
$conn = $db->connect();

$assetController = new AssetController($conn);

$assets = $assetController->index();

require __DIR__ . '/../layouts/header.php';
require __DIR__ . '/../layouts/sidebar.php';
require __DIR__ . '/../layouts/navbar.php';

?>

<div class="ml-64 p-8">
<?php if (isset($_GET['success'])): ?>

<div class="bg-green-100 border border-green-500 text-green-700 p-4 rounded mb-5">

    <h3 class="font-bold">

        ✅ Asset Added Successfully

    </h3>

    <p>

        The asset has been saved to the database successfully.

    </p>

</div>

<?php endif; ?>


    <div class="bg-white p-6 rounded-lg shadow">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-3xl font-bold text-gray-800">

                Asset Management

            </h1>

            <a
                href="?page=asset-create"
                class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">

                + Add Asset

            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full border border-gray-300">

                <thead class="bg-gray-200">

                    <tr>

                        <th class="border p-3">Asset Tag</th>
                        <th class="border p-3">Serial Number</th>
                        <th class="border p-3">Asset Name</th>
                        <th class="border p-3">Category</th>
                        <th class="border p-3">Brand</th>
                        <th class="border p-3">Model</th>
                        <th class="border p-3">Vendor</th>
                        <th class="border p-3">Purchase Date</th>
                        <th class="border p-3">Warranty Expiry</th>
                        <th class="border p-3">Status</th>
                        <th class="border p-3">Remarks</th>
                        <th class="border p-3">Action</th>

                    </tr>

                </thead>

                <tbody>

                <?php if (!empty($assets)): ?>

                    <?php foreach ($assets as $asset): ?>

                        <tr class="hover:bg-gray-50">

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['asset_tag']) ?>
                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['serial_number']) ?>
                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['asset_name']) ?>
                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['category']) ?>
                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['brand']) ?>
                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['model']) ?>
                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['company_name'] ?? 'N/A') ?>
                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['purchase_date']) ?>
                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['warranty_expiry']) ?>
                            </td>

                            <td class="border p-3">

                                <?php if ($asset['status'] == 'Available'): ?>

                                    <span class="text-green-600 font-semibold">
                                        <?= htmlspecialchars($asset['status']) ?>
                                    </span>

                                <?php else: ?>

                                    <span class="text-red-600 font-semibold">
                                        <?= htmlspecialchars($asset['status']) ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="border p-3">
                                <?= htmlspecialchars($asset['remarks']) ?>
                            </td>

                            <td class="border p-3">

                                <a
                                    href="?page=asset-edit&id=<?= $asset['id'] ?>"
                                    class="text-blue-600 font-medium">

                                    Edit

                                </a>

                                |

                                <a
                                    href="?page=asset-delete&id=<?= $asset['id'] ?>"
                                    class="text-red-600 font-medium">

                                    Delete

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="12" class="text-center p-5 text-gray-500">

                            No Assets Found

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php

require __DIR__ . '/../layouts/footer.php';

?>
