<?php
namespace App\Helpers;
class Router
{
    public static function route()
    {
        $page = $_GET['page'] ?? 'dashboard';

        switch ($page)
        {
            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */
            case 'dashboard':
                require __DIR__ . '/../Views/dashboard/index.php';
                break;

            

    break;    
            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */
            case 'login':
                require __DIR__ . '/../Views/auth/login.php';
                break;

            /*
            |--------------------------------------------------------------------------
            | Departments
            |--------------------------------------------------------------------------
            */
            case 'departments':
                require __DIR__ . '/../Views/departments/index.php';
                break;

            case 'department-create':
                require __DIR__ . '/../Views/departments/create.php';
                break;

            case 'department-edit':
                require __DIR__ . '/../Views/departments/edit.php';
                break;

            case 'department-delete':
                require __DIR__ . '/../Views/departments/delete.php';
                break;

            /*
            |--------------------------------------------------------------------------
            | Employees
            |--------------------------------------------------------------------------
            */
            case 'employees':
                require __DIR__ . '/../Views/employees/index.php';
                break;

            case 'employee-create':
                require __DIR__ . '/../Views/employees/create.php';
                break;

            case 'employee-edit':
                require __DIR__ . '/../Views/employees/edit.php';
                break;

            case 'employee-delete':
                require __DIR__ . '/../Views/employees/delete.php';
                break;

            /*
            |--------------------------------------------------------------------------
            | Vendors
            |--------------------------------------------------------------------------
            */
            case 'vendors':
                require __DIR__ . '/../Views/vendors/index.php';
                break;

            case 'vendor-create':
                require __DIR__ . '/../Views/vendors/create.php';
                break;

            case 'vendor-edit':
                require __DIR__ . '/../Views/vendors/edit.php';
                break;

            case 'vendor-delete':
                require __DIR__ . '/../Views/vendors/delete.php';
                break;

            /*
            |--------------------------------------------------------------------------
            | Assets

/*
|--------------------------------------------------------------------------
| Assets
|--------------------------------------------------------------------------
*/

case 'assets':
    require __DIR__ . '/../Views/assets/index.php';
    break;

case 'asset-create':
    require __DIR__ . '/../Views/assets/create.php';
    break;

case 'asset-edit':
    require __DIR__ . '/../Views/assets/edit.php';
    break;

case 'asset-delete':
    require __DIR__ . '/../Views/assets/delete.php';
    break;
case 'asset-search':
    require_once __DIR__ . '/../Views/assets/search.php';
    break;

case 'asset-show':
    require_once __DIR__ . '/../Views/assets/show.php';
    break;    

            /*
            |--------------------------------------------------------------------------
            | Assignments
            |--------------------------------------------------------------------------
            */
            case 'asset-assignments':
    require_once __DIR__ . '/../Views/asset_assignments/index.php';
    break;

case 'asset-assignment-create':
    require_once __DIR__ . '/../Views/asset_assignments/create.php';
    break;

case 'asset-assignment-show':
    require_once __DIR__ . '/../Views/asset_assignments/show.php';
    break;

case 'asset-assignment-edit':
    require_once __DIR__ . '/../Views/asset_assignments/edit.php';
    break;

case 'asset-assignment-return':
    require_once __DIR__ . '/../Views/asset_assignments/return.php';
    break;
case 'asset-assignment-store':

    $controller = new \App\Controllers\AssetAssignmentController();

    $controller->store();

    break;

case 'asset-assignment-update':

    $controller = new \App\Controllers\AssetAssignmentController();

    $controller->update($_GET['id'], $_POST);

    break;

case 'asset-assignment-return-save':

    $controller = new \App\Controllers\AssetAssignmentController();

    $controller->returnAsset($_GET['id'], $_POST);

    break;

case 'asset-assignment-delete':

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $controller = new \App\Controllers\AssetAssignmentController();

        $controller->delete($_GET['id']);

    } else {

        require_once __DIR__ . '/../Views/asset_assignments/delete.php';

    }

    break;   

            /*
            |--------------------------------------------------------------------------
            | setting
            |--------------------------------------------------------------------------
            */
            case 'settings':
    require_once __DIR__.'/../Views/settings/index.php';
    break;

case 'settings-company':
    require_once __DIR__.'/../Views/settings/company.php';
    break;

case 'settings-profile':
    require_once __DIR__.'/../Views/settings/profile.php';
    break;

case 'settings-categories':
    require_once __DIR__.'/../Views/settings/categories.php';
    break;

case 'settings-locations':
    require_once __DIR__.'/../Views/settings/locations.php';
    break;

case 'settings-manufacturers':
    require_once __DIR__.'/../Views/settings/manufacturers.php';
    break;

case 'settings-status':
    require_once __DIR__.'/../Views/settings/asset_status.php';
    break;

case 'settings-email':
    require_once __DIR__.'/../Views/settings/email.php';
    break;

case 'settings-backup':
    require_once __DIR__.'/../Views/settings/backup.php';
    break;

case 'settings-security':
    require_once __DIR__.'/../Views/settings/security.php';
    break;
case 'settings-category-edit':
    require_once __DIR__ . '/../Views/settings/category_edit.php';
    break;

case 'settings-category-delete':
    require_once __DIR__ . '/../Views/settings/category_delete.php';
    break;    
case 'settings-status':

require_once __DIR__.'/../Views/settings/asset_status.php';

break;

case 'asset-status-edit':

require_once __DIR__.'/../Views/settings/asset_status_edit.php';

break;

case 'asset-status-delete':

require_once __DIR__.'/../Views/settings/asset_status_delete.php';

break;    
            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            case 'reports':

require_once __DIR__ . '/../Views/reports/index.php';

break;

case 'report-assets':

require_once __DIR__ . '/../Views/reports/assets.php';

break;

case 'report-assignments':

require_once __DIR__ . '/../Views/reports/assignment.php';

break;

case 'report-employees':

require_once __DIR__ . '/../Views/reports/employees.php';

break;

case 'report-vendors':

require_once __DIR__ . '/../Views/reports/vendors.php';

break;

case 'report-warranty':

require_once __DIR__ . '/../Views/reports/warranty.php';

break;
case 'report-purchase':

require_once __DIR__ . '/../Views/reports/purchase.php';

break;
            /*
            |--------------------------------------------------------------------------
            | Handovers
            |--------------------------------------------------------------------------
            */
            case 'handovers':
                require __DIR__ . '/../Views/handovers/index.php';
                break;

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            case 'reports':
                require __DIR__ . '/../Views/reports/index.php';
                break;

            /*
            |--------------------------------------------------------------------------
            | 404
            |--------------------------------------------------------------------------
            */
            default:
                echo "<h1>404 Page Not Found</h1>";
                break;
        }
    }
}

