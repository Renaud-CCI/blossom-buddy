<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserPlantController;

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


// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'This is a test route']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Registration
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::post('/login', [AuthController::class, 'login']);
Route::get('/', function () { return view('welcome');})->name('welcome');

// Logout
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Récupérer les informations de toutes les plantes
Route::get('/plant', [PlantController::class, 'index']);

// Ajouter une plante
Route::post('/plant', [PlantController::class, 'store']);

// Récupérer les informations d'une plante par son nom
Route::get('/plant/{name}', [PlantController::class, 'showByName']);

// Supprimer une plante par son ID
Route::delete('/plant/{id}', [PlantController::class, 'destroy']);

// Permettre à un utilisateur d'entrer la plante qu'il a et l'endroit où il est
Route::post('/user/plant', [UserPlantController::class, 'store'])->middleware('auth:sanctum');

// Permettre à l'utilisateur de supprimer une plante qu'il a indiqué posséder
Route::delete('/user/plant/{id}', [UserPlantController::class, 'destroy'])->middleware('auth:sanctum');
