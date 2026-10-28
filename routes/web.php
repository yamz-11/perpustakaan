<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('peminjamen.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('peminjamen.index');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/bukus', [BukuController::class, 'index'])->name('bukus.index');
    // Route::get('/bukus/create', [BukuController::class, 'create'])->name('bukus.create');
    // Route::get('/bukus/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    // Route::patch('/bukus/{buku}', [BukuController::class, 'update'])->name('buku.update');
    // Route::delete('/bukus/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    Route::resource('/bukus', BukuController::class);

    Route::get('/peminjamen', [PeminjamanController::class, 'index'])->name('peminjamen.index');
    Route::get('/peminjamen/{buku}', [PeminjamanController::class, 'create'])->name('peminjamen.create');
    Route::post('/peminjamen/{buku}', [PeminjamanController::class, 'store'])->name('peminjamen.store');
    Route::get('/peminjamen/terminate/{peminjaman}', [PeminjamanController::class, 'terminate'])->name('peminjamen.terminate');

    Route::resource('/category', CategoryController::class);

    Route::middleware('admin')->group(function() {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::patch('/user/{user}/makeadmin', [UserController::class, 'makeadmin'])->name('user.makeadmin');
        Route::patch('/user/{user}/removeadmin', [UserController::class, 'removeadmin'])->name('user.removeadmin');

    });
});

require __DIR__ . '/auth.php';
