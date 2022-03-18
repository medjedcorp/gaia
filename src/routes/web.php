<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainImportController;
use App\Http\Controllers\TrainCsvExportController;
use App\Http\Controllers\LineCsvExportController;
use App\Http\Controllers\StationCsvExportController;
use App\Http\Controllers\LineImportController;
use App\Http\Controllers\StationImportController;
use App\Http\Controllers\LandAdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
// use App\Http\Middleware\UserAccept;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/registered', [UserController::class, 'store']);
Route::get('/auth/thanks', [UserController::class, 'store']);
Route::post('/auth/thanks', [UserController::class, 'store'])->name('users.store');
// Route::get('/regist-customer', [RegistCustomerController::class, 'create']);
// Route::get('/dashboard', function () {
//     return view('index');
// })->middleware(['auth'])->name('dashboard');
Route::middleware(['auth', 'UserAccept'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/logout',  [AuthenticatedSessionController::class, 'destroy']);
    Route::get('/maps', function () {
        return view('maps');
    });
    // admin限定機能
    Route::middleware(['auth', 'AdminAccept'])->group(function () {
        Route::post('/users', [UserController::class, 'index'])->name('users.approval');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/admin/lands', [LandAdminController::class, 'index'])->name('admin.lands.index');
        Route::get('/admin/lands/{bukken_num}', [LandAdminController::class, 'show'])->name('admin.lands.show');
        Route::get('/admin/lands/download', [LandAdminController::class, 'pdfDownload'])->name('admin.lands.pdfdownload');
        // system 限定機能
        Route::middleware(['auth', 'SystemAccept'])->group(function () {
            Route::get('/csv/train', [TrainImportController::class, 'showTrain'])->name('showTrain');
            Route::post('/csv/train', [TrainImportController::class, 'importTrainCSV'])->name('importTrainCSV');
            Route::get('/csv/line', [LineImportController::class, 'showLine'])->name('showLine');
            Route::post('/csv/line', [LineImportController::class, 'importLineCSV'])->name('importLineCSV');
            Route::get('/csv/station', [StationImportController::class, 'showStation'])->name('showStation');
            Route::post('/csv/station', [StationImportController::class, 'importStationCSV'])->name('importStationCSV');
            Route::get('/csv/train/download', [TrainCsvExportController::class, 'download'])->name('exportTrainCSV');
            Route::get('/csv/line/download', [LineCsvExportController::class, 'download'])->name('exportLineCSV');
            Route::get('/csv/station/download', [StationCsvExportController::class, 'download'])->name('exportStationCSV');
        });
    });
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

// Route::get('/login', function () {    return view('login');
// });

Route::get('/index', function () {
    return view('index');
});


Route::get('/errors-404-error', function () {
    return view('errors-404-error');
});
Route::get('/errors-500-error', function () {
    return view('errors-500-error');
});

// sample
// Route::get('/', function () {
//     return view('index');
// });
Route::get('/index', function () {
    return view('index');
});
Route::get('/dashboard-alternate', function () {
    return view('dashboard-alternate');
});
/*App*/
Route::get('/app-emailbox', function () {
    return view('app-emailbox');
});
Route::get('/app-emailread', function () {
    return view('app-emailread');
});
Route::get('/app-chat-box', function () {
    return view('app-chat-box');
});
Route::get('/app-file-manager', function () {
    return view('app-file-manager');
});
Route::get('/app-contact-list', function () {
    return view('app-contact-list');
});
Route::get('/app-to-do', function () {
    return view('app-to-do');
});
Route::get('/app-invoice', function () {
    return view('app-invoice');
});
Route::get('/app-fullcalender', function () {
    return view('app-fullcalender');
});
/*Charts*/
Route::get('/charts-apex-chart', function () {
    return view('charts-apex-chart');
});
Route::get('/charts-chartjs', function () {
    return view('charts-chartjs');
});
Route::get('/charts-highcharts', function () {
    return view('charts-highcharts');
});
/*ecommerce*/
Route::get('/ecommerce-products', function () {
    return view('ecommerce-products');
});
Route::get('/ecommerce-products-details', function () {
    return view('ecommerce-products-details');
});
Route::get('/ecommerce-add-new-products', function () {
    return view('ecommerce-add-new-products');
});
Route::get('/ecommerce-orders', function () {
    return view('ecommerce-orders');
});

/*Components*/
Route::get('/widgets', function () {
    return view('widgets');
});
Route::get('/component-alerts', function () {
    return view('component-alerts');
});
Route::get('/component-accordions', function () {
    return view('component-accordions');
});
Route::get('/component-badges', function () {
    return view('component-badges');
});
Route::get('/component-buttons', function () {
    return view('component-buttons');
});
Route::get('/component-cards', function () {
    return view('component-cards');
});
Route::get('/component-carousels', function () {
    return view('component-carousels');
});
Route::get('/component-list-groups', function () {
    return view('component-list-groups');
});
Route::get('/component-media-object', function () {
    return view('component-media-object');
});
Route::get('/component-modals', function () {
    return view('component-modals');
});
Route::get('/component-navs-tabs', function () {
    return view('component-navs-tabs');
});
Route::get('/component-navbar', function () {
    return view('component-navbar');
});
Route::get('/component-paginations', function () {
    return view('component-paginations');
});
Route::get('/component-popovers-tooltips', function () {
    return view('component-popovers-tooltips');
});
Route::get('/component-progress-bars', function () {
    return view('component-progress-bars');
});
Route::get('/component-spinners', function () {
    return view('component-spinners');
});
Route::get('/component-notifications', function () {
    return view('component-notifications');
});
Route::get('/component-avtars-chips', function () {
    return view('component-avtars-chips');
});
/*Content*/
Route::get('/content-grid-system', function () {
    return view('content-grid-system');
});
Route::get('/content-typography', function () {
    return view('content-typography');
});
Route::get('/content-text-utilities', function () {
    return view('content-text-utilities');
});
/*Icons*/
Route::get('/icons-line-icons', function () {
    return view('icons-line-icons');
});
Route::get('/icons-boxicons', function () {
    return view('icons-boxicons');
});
Route::get('/icons-feather-icons', function () {
    return view('icons-feather-icons');
});
/*Authentication*/
Route::get('/authentication-signin', function () {
    return view('authentication-signin');
});
Route::get('/authentication-signup', function () {
    return view('authentication-signup');
});
Route::get('/authentication-signin-with-header-footer', function () {
    return view('authentication-signin-with-header-footer');
});
Route::get('/authentication-signup-with-header-footer', function () {
    return view('authentication-signup-with-header-footer');
});
Route::get('/authentication-forgot-password', function () {
    return view('authentication-forgot-password');
});
Route::get('/authentication-reset-password', function () {
    return view('authentication-reset-password');
});
Route::get('/authentication-lock-screen', function () {
    return view('authentication-lock-screen');
});
/*Table*/
Route::get('/table-basic-table', function () {
    return view('table-basic-table');
});
Route::get('/table-datatable', function () {
    return view('table-datatable');
});
/*Pages*/
Route::get('/user-profile', function () {
    return view('user-profile');
});
Route::get('/timeline', function () {
    return view('timeline');
});
Route::get('/pricing-table', function () {
    return view('pricing-table');
});

Route::get('/errors-coming-soon', function () {
    return view('errors-coming-soon');
});
Route::get('/error-blank-page', function () {
    return view('error-blank-page');
});
Route::get('/faq', function () {
    return view('faq');
});
/*Forms*/
Route::get('/form-elements', function () {
    return view('form-elements');
});

Route::get('/form-input-group', function () {
    return view('form-input-group');
});
Route::get('/form-layouts', function () {
    return view('form-layouts');
});
Route::get('/form-validations', function () {
    return view('form-validations');
});
Route::get('/form-wizard', function () {
    return view('form-wizard');
});
Route::get('/form-text-editor', function () {
    return view('form-text-editor');
});
Route::get('/form-file-upload', function () {
    return view('form-file-upload');
});
Route::get('/form-date-time-pickes', function () {
    return view('form-date-time-pickes');
});
Route::get('/form-select2', function () {
    return view('form-select2');
});
/*Maps*/
Route::get('/map-google-maps', function () {
    return view('map-google-maps');
});
Route::get('/map-vector-maps', function () {
    return view('map-vector-maps');
});
/*Un-found*/
Route::get('/test/content-grid-system', function () {
    return view('test/content-grid-system');
});
