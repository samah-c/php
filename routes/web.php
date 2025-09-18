<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\UtilisateurController as AdminUserController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\User\DashboardController;

Route::get('/', function () {
    return inertia('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::get('/dashboard', function () {
    return inertia('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin area protected by admin middleware
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', function () { return redirect()->route('admin.units.index'); })->name('home');

        Route::resource('units', UnitController::class)->except(['show']);
        Route::resource('groups', GroupController::class)->except(['show']);
        Route::resource('agents', AgentController::class)->except(['show']);
        Route::resource('users', AdminUserController::class)->except(['show']);

        // Assignment actions
        Route::post('agents/{agent}/assign-group', [AgentController::class, 'assignGroup'])->name('agents.assignGroup');
        Route::post('groups/{group}/set-supervisor', [GroupController::class, 'setSupervisor'])->name('groups.setSupervisor');
        Route::post('users/{user}/assign-unit', [AdminUserController::class, 'assignUnit'])->name('users.assignUnit');
    });
});

// User authentication routes
Route::prefix('user')->name('user.')->group(function () {
    Route::middleware('guest:utilisateur')->group(function () {
        Route::get('login', [UserLoginController::class, 'create'])->name('login');
        Route::post('login', [UserLoginController::class, 'store']);
    });
    
   
    Route::middleware(['auth:utilisateur', 'ensure-utilisateur'])->group(function () {
        Route::post('logout', [UserLoginController::class, 'destroy'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});

require __DIR__.'/auth.php';