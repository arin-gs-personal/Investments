<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\LoginController;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;


Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/stdinfo', function () {
        return view('stdinfo');
    });
});


Route::get('/', function(){
    return redirect('/login');
});
Route::get('/admission', [AdmissionController::class, 'create'] );
Route::post('/admission', [AdmissionController::class, 'store'] );
Route::get('/stdinfo', [AdmissionController::class, 'index'])->name('stdinfo');

Route::get('/rate', [RateController::class, 'create'] );
Route::post('/rate', [RateController::class, 'store'] );

Route::get('/stdinfo', [AdmissionController::class, 'index'])->name('stdinfo');




Route::get('/login', [LoginController::class, 'showLogin']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');

Route::get('/signup', [LoginController::class, 'showSignup']);
Route::post('/signup', [LoginController::class, 'signup']);

Route::middleware('auth')->group(function () {
    Route::post('/admission/store', [AdmissionController::class, 'store']);
});






// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Route::middleware('auth')->group(function () {
//     Route::get('/stdinfo', [AdmissionController::class, 'index']);
//     Route::get('/admission', [AdmissionController::class, 'create']);
//     Route::post('/admission/store', [AdmissionController::class, 'store']);
// });

// Auth::routes();
