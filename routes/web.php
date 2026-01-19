<?php

declare(strict_types=1);

use App\Livewire\Pages\Home;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', Home::class)->name('home');
Route::get('/anime/{slug}', App\Livewire\Anime\Show::class)->name('anime.show');
Route::get('/izle/{anime:slug}/{segment1}/{segment2?}', App\Livewire\Anime\Watch::class)->name('anime.watch');
