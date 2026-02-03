<?php

declare(strict_types=1);

use App\Livewire\Pages\Home;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::livewire('/animeler', 'anime.hub')->name('anime.hub');
Route::livewire('/filmler', 'movie.index')->name('movie.index');
Route::livewire('/', 'pages.home')->name('home');
Route::livewire('/profil/{username?}', 'profile')->name('profile');
Route::livewire('/anime/{slug}', 'anime.show')->name('anime.show');
Route::livewire('/izle/{anime:slug}/{segment1}/{segment2?}', 'anime.watch')->name('anime.watch');
Route::livewire('/kesfet', 'anime.discover')->name('anime.discover');
Route::livewire('/takvim', 'anime.calendar')->name('anime.calendar');
