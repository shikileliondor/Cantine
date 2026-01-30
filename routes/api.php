<?php

use App\Http\Controllers\Modules\ContactParentController;
use App\Http\Controllers\Modules\EleveController;
use App\Http\Controllers\Modules\FactureController;
use App\Http\Controllers\Modules\NoteEleveController;
use App\Http\Controllers\Modules\PaiementController;
use App\Http\Controllers\Modules\RemiseController;
use Illuminate\Support\Facades\Route;

Route::apiResource('eleves', EleveController::class);
Route::apiResource('contacts-parents', ContactParentController::class)
    ->parameters(['contacts-parents' => 'contactParent']);
Route::apiResource('notes-eleves', NoteEleveController::class)
    ->parameters(['notes-eleves' => 'noteEleve']);
Route::apiResource('remises', RemiseController::class);
Route::apiResource('factures', FactureController::class);
Route::apiResource('paiements', PaiementController::class);
