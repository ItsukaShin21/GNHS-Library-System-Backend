<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SubjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum','api'])->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('api')->group(function() {
    Route::post("/login", [AuthController::class, 'login']);
    Route::post("/upload", [BookController::class, 'upload']);
    Route::post("/delete", [BookController::class, 'deleteBook']);
    Route::post("/record-login", [AuthController::class, 'user_record_login']);
    Route::post("/record-logout", [AuthController::class, 'user_record_logout']);
    Route::post("/register", [AuthController::class, 'userRegister']);
    Route::post("/update-book", [BookController::class, 'update']);
    Route::post("/record-book", [BookController::class, 'book_record_open']);
    Route::get("/fetch-subjects", [SubjectController::class, 'fetchSubjects']);
    Route::get("/fetch-books", [BookController::class, 'fetchBooks']);
    Route::get("/fetch-book-details/{book_id}", [BookController::class, 'fetchBookDetails']);
    Route::get("/fetch-login-logs", [AuthController::class, 'fetchLoginRecords']);
    Route::get("/fetch-book-logs", [BookController::class, 'fetchBookLogRecords']);
});
