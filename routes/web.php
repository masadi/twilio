<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;
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
    return redirect('https://erapor.ditpsmk.net/');
    return view('welcome');
});
//Route::get('whatsapp', [WhatsAppController::class, 'index']);
//Route::post('whatsapp', [WhatsAppController::class, 'store'])->name('whatsapp.post');
