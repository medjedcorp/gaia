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
use App\Http\Controllers\LandUserController;
use App\Http\Controllers\UserMapsController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\RailroadController;
use App\Http\Controllers\AjaxGetLandController;
use App\Http\Controllers\TestController;
// use App\Http\Controllers\ResultController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth;
// use App\Http\Middleware\UserAccept;

// Route::get('login', [Auth\LoginController::class, 'showLoginForm'])->name('login');

Route::get('/', function () {
    // return view('auth.login');
    return redirect() -> route('login');
});
// Route::get('/registered', [UserController::class, 'store']);
Route::get('/auth/thanks', [UserController::class, 'store']);
Route::post('/auth/thanks', [UserController::class, 'store'])->name('users.store');
// Route::get('/regist-customer', [RegistCustomerController::class, 'create']);
// Route::get('/dashboard', function () {
//     return view('index');
// })->middleware(['auth'])->name('dashboard');
Route::middleware(['auth', 'UserAccept'])->group(function () {
    // Route::get('/index', function () {
    //     return view('index');
    // });
    // Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/railroad', [RailroadController::class, 'index']);
    Route::get('/railroad/list', [RailroadController::class, 'lists'])->name('rail.lists');

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/address', [AddressController::class, 'index']);
    Route::get('/address/list', [AddressController::class, 'lists'])->name('ad.lists');
    
    Route::get('/logout',  [AuthenticatedSessionController::class, 'destroy']);
    Route::get('/lands/map', [UserMapsController::class, 'index'])->name('users.maps');
    Route::get('/lands/show/{bukken_num}', [LandUserController::class, 'show'])->name('user.land.show');
    Route::get('/lands/thanks', [LandUserController::class, 'thanks']);
    Route::post('/lands/thanks', [LandUserController::class, 'thanks'])->name('users.land.thanks');
    Route::get('/lands/index', [LandUserController::class, 'index'])->name('users.lands.index');
    Route::get('/lands/new', [LandUserController::class, 'new'])->name('users.lands.new');
    Route::get('/getlands', [AjaxGetLandController::class, 'getLand'])->name('lands.getlands');
    // admin限定機能
    Route::middleware(['auth', 'AdminAccept'])->group(function () {
        Route::post('/users', [UserController::class, 'index'])->name('users.approval');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/admin/lands', [LandAdminController::class, 'index'])->name('admin.lands.index');
        Route::get('/admin/lands/download', [LandAdminController::class, 'pdfDownload'])->name('admin.lands.pdfdownload');
        Route::get('/admin/lands/{bukken_num}', [LandAdminController::class, 'show'])->name('admin.lands.show');
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

require __DIR__ . '/auth.php';

Route::get('/errors-404-error', function () {
    return view('errors-404-error');
});
Route::get('/errors-500-error', function () {
    return view('errors-500-error');
});


Route::get('/errors-coming-soon', function () {
    return view('errors-coming-soon');
});
Route::get('/error-blank-page', function () {
    return view('error-blank-page');
});

/*Un-found*/
Route::get('/test/content-grid-system', function () {
    return view('test/content-grid-system');
});
