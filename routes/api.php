<?php

use App\Http\Controllers\EmailController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::bind('user', function($value) {
    return User::findOrFail($value);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('{user}/send', [EmailController::class, 'send'])->name('email.send');
    Route::get('list', [EmailController::class, 'list'])->name('email.send');
});

Route::get('generate-token', function() {
    $user = User::first();
    Auth::login($user);
    return str_replace("|", "%7C", $user->createToken('api-token')->plainTextToken);
});

