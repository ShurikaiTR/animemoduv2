<?php

declare(strict_types=1);

use App\Livewire\Pages\Home;
use App\Livewire\Profile\Show;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/animeler', App\Livewire\Anime\Hub::class)->name('anime.hub');
Route::get('/filmler', App\Livewire\Movie\Index::class)->name('movie.index');
Route::get('/', App\Livewire\Pages\Home::class)->name('home');
Route::get('/profil/{username?}', Show::class)->name('profile.show');
Route::get('/anime/{slug}', App\Livewire\Anime\Show::class)->name('anime.show');
Route::get('/izle/{anime:slug}/{segment1}/{segment2?}', App\Livewire\Anime\Watch::class)->name('anime.watch');
Route::get('/kesfet', App\Livewire\Anime\Discover::class)->name('anime.discover');
Route::get('/takvim', App\Livewire\Anime\Calendar::class)->name('anime.calendar');
