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

