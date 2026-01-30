@props(['activity', 'username', 'index'])

@php
    $animePoster = $activity->anime ? ($activity->anime->poster_path ? 'https://image.tmdb.org/t/p/w200' . $activity->anime->poster_path : null) : null;

    $icon = match ($activity->type) {
        'watched' => 'monitor-play',
        'watching' => 'play',
        'plan_to_watch' => 'calendar',
        'completed' => 'check',
        'favorite' => 'heart',
        default => 'clock'
    };

    // Activity Text Logic
    $activityText = '';
    switch ($activity->type) {
        case 'watched':
            $activityText = 'bir bölüm izledi';
            break;
        case 'watching':
            $activityText = 'izlemeye başladı';
            break;
        case 'plan_to_watch':
            $activityText = 'izlemeyi planlıyor';
            break;
        case 'completed':
            $activityText = 'tamamladı';
            break;
        case 'dropped':
            $activityText = 'bıraktı';
            break;
        case 'favorite':
            $activityText = 'favorilere ekledi';
            break;
        default:
            $activityText = 'bir işlem yaptı';
    }
@endphp

<div class="relative pl-6 pb-6 last:pb-0 border-l border-white/10 last:border-0 ml-2 animate-fade-in-up"
    style="animation-delay: {{ $index * 100 }}ms">
    <div class="absolute -left-1 top-0 w-2.5 h-2.5 rounded-full bg-white/20 ring-4 ring-base-900"></div>

    <div class="flex gap-4">
        @if($animePoster)
            <a href="{{ route('anime.show', $activity->anime->slug) }}"
                class="shrink-0 w-12 h-16 relative rounded-lg overflow-hidden border border-white/10 group">
                <img src="{{ $animePoster }}" alt="{{ $activity->anime->title }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform">
            </a>
        @endif

        <div class="flex-1">
            <div class="text-sm text-white/50 mb-1 flex items-center gap-2">
                {{-- Icon would go here --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                <span>{{ $activity->created_at->diffForHumans() }}</span>
            </div>

            <p class="text-white text-sm leading-relaxed">
                <span class="font-bold text-primary">{{ $username }}</span>
                <span class="text-gray-300">{{ $activityText }}</span>
                @if($activity->anime)
                    <a href="{{ route('anime.show', $activity->anime->slug) }}"
                        class="font-bold text-white hover:text-primary transition-colors ml-1">{{ $activity->anime->title }}</a>
                @endif
            </p>
        </div>
    </div>
</div>