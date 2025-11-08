<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserBeneficiaryController;

Route::post('/user-beneficiary', [\App\Http\Controllers\Api\UserBeneficiaryController::class, 'store']);
?>

