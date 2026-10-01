<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
  Route::inertia('dashboard', 'dashboard')->name('dashboard');

  // -- People administration (RH & admins only) --------------------------
  Route::middleware(['role:admin,hr'])->group(function () {
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('departments', [DepartmentController::class, 'store'])->name('departments.store');
    Route::patch('departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
    Route::delete('departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    Route::get('positions', [PositionController::class, 'index'])->name('positions.index');
    Route::post('positions', [PositionController::class, 'store'])->name('positions.store');
    Route::patch('positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
  });

  // -- RH, admins, managers only --------------------------
  Route::middleware(['role:admin,hr,manager'])->group(function () {
    Route::get('employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
  });

  // -- Only RH & admins can create, edit or remove employees. --------------------------
  Route::middleware('role:admin,hr')->group(function () {
    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::patch('employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
  });

  // -- Leave Types are configured by RH & admins. --------------------------
  Route::middleware('role:admin,hr')->group(function () {
    Route::get('leave-types', [LeaveTypeController::class, 'index'])->name('leave-types.index');
    Route::post('leave-types', [LeaveTypeController::class, 'store'])->name('leave-types.store');
    Route::patch('leave-types/{leaveType}', [LeaveTypeController::class, 'update'])->name('leave-types.update');
    Route::delete('leave-types/{leaveType}', [LeaveTypeController::class, 'destroy'])->name('leave-types.destroy');
  });

});

require __DIR__.'/settings.php';
