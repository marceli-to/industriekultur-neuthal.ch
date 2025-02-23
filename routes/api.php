<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\PatronController;
use App\Http\Controllers\Api\ParticipantController;
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

Route::post('/event/register', [EventController::class, 'register']);
Route::get('/event/{eventId}', [EventController::class, 'get']);
Route::post('/contact/submission', [ContactController::class, 'submission']);
Route::post('/patron/submission', [PatronController::class, 'submission']);
Route::post('/participant/submission', [ParticipantController::class, 'submission']);