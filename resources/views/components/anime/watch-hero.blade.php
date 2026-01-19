@props(['anime'])

@if($anime->backdrop_path)
    <div class="absolute top-0 left-0 right-0 h-[60vh] max-h-[37.5rem] w-full z-0 pointer-events-none">
        <img src="{{ \App\Services\TmdbService::getImageUrl($anime->backdrop_path, 'w1280') }}"
            alt="{{ $anime->title }} backdrop" class="w-full h-full object-cover opacity-20 select-none">
        <div class="absolute inset-0 bg-gradient-to-b from-bg-main/10 via-bg-main/80 to-bg-main z-10"></div>
    </div>
@endif