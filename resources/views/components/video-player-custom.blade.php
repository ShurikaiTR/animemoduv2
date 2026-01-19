@props([
    'src' => null,
    'poster' => null,
    'anime' => null,
    'episode' => null,
    'logo' => null
])

<div 
    x-data="videoPlayer({
        src: '{{ $src }}',
        poster: '{{ $poster }}',
        logo: '{{ $logo }}',
        animeTitle: '{{ $anime?->title }}',
        episodeTitle: '{{ $episode?->season_number }}. Sezon {{ $episode?->episode_number }}. Bölüm'
    })"
    class="w-full h-full rounded-xl overflow-hidden bg-black relative z-10 group"
>
    {{-- Loading State --}}
    <template x-if="!isReady">
        <div class="w-full h-full flex items-center justify-center bg-black">
            <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
        </div>
    </template>

    {{-- Player Header Overlay --}}
    <div 
        x-show="!isReady || showOverlay" 
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute top-6 left-6 z-40 flex items-center gap-4 pointer-events-none select-none"
    >
        <template x-if="logo">
            <div class="relative">
                <div class="absolute inset-0 rounded-full bg-primary/40 animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite] opacity-50"></div>
                <div class="relative w-14 h-14 rounded-full overflow-hidden border-2 border-white/10 shadow-xl bg-black/50 backdrop-blur-md z-10">
                    <img :src="logo" :alt="animeTitle" class="w-full h-full object-cover">
                </div>
            </div>
        </template>
        
        <div class="flex flex-col gap-1 drop-shadow-md">
            <h3 x-text="animeTitle" class="text-white font-bold text-xl leading-none tracking-wide font-rubik text-shadow-lg"></h3>
            <p x-text="episodeTitle" class="text-white/90 text-sm font-medium tracking-wide text-shadow-md"></p>
        </div>
    </div>

    {{-- Player Container --}}
    <div x-show="isReady" class="w-full h-full relative">
        <video
            x-ref="video"
            class="video-js vjs-big-play-centered"
            style="width: 100%; height: 100%;"
            playsinline
            preload="auto"
        ></video>
    </div>
</div>

@assets
<script src="/player/nsvideo.min.js" strategy="lazyOnload"></script>
<link href="/player/skins/flow/videojs.min.css" rel="stylesheet">
@endassets

@script
<script>
    Alpine.data('videoPlayer', (config) => ({
        player: null,
        isReady: false,
        showOverlay: true,
        animeTitle: config.animeTitle,
        episodeTitle: config.episodeTitle,
        logo: config.logo,
        
        init() {
            this.loadLanguages().then(() => {
                this.initPlayer();
            });

            Livewire.on('play-episode', (data) => {
                this.animeTitle = data.anime_title;
                this.episodeTitle = data.episode_title;
                this.logo = data.logo;

                if (this.player && this.isReady) {
                    this.player.poster(data.poster);
                    this.player.src({ 
                        type: data.src.includes('.m3u8') ? 'application/x-mpegURL' : 'video/mp4', 
                        src: data.src 
                    });
                    this.player.play();
                }
            });
        },

        async loadLanguages() {
            if (window.videojs) return;
            
            // Wait for videojs to be available (loaded by nsvideo.min.js)
            let checkCount = 0;
            while(!window.videojs && checkCount < 50) {
                await new Promise(r => setTimeout(r, 100));
                checkCount++;
            }

            // Load Turkish language
            if(window.videojs && !document.querySelector('script[src*="tr.js"]')) {
                const script = document.createElement('script');
                script.src = '/player/lang/tr.js';
                document.body.appendChild(script);
                await new Promise(r => script.onload = r);
            }
        },

        initPlayer() {
            if (!window.videojs) {
                console.error('VideoJS not loaded');
                return;
            }

            this.player = videojs(this.$refs.video, {
                controls: true,
                autoplay: false,
                preload: 'auto',
                fluid: true,
                poster: config.poster,
                sources: [{
                    src: config.src,
                    type: config.src?.includes('.m3u8') ? 'application/x-mpegURL' : 'video/mp4'
                }],
                language: 'tr'
            });

            this.player.ready(() => {
                this.isReady = true;

                this.player.on('play', () => { this.showOverlay = false; });
                this.player.on('pause', () => { this.showOverlay = true; });
                this.player.on('ended', () => { this.showOverlay = true; });

                if (this.player.nuevo) {
                    this.player.nuevo({
                        skin: 'flow',
                        title: this.animeTitle, // Use current title
                        settingsButton: true,
                        shareMenu: true,
                        rateMenu: true,
                        zoomMenu: true,
                        sleepTimerMenu: true,
                        relatedMenu: false,
                        tooltips: true,
                        contextMenu: true,
                        contextLink: true,
                        contextUrl: 'https://animemodu.com',
                        contextText: 'animemodu',
                        pipButton: true,
                        fullscreenButton: true,
                        buttonRewind: true,
                        buttonForward: false,
                        touchControls: true,
                        logo: this.logo
                    });
                }
            });
        },

        destroy() {
            if (this.player) {
                this.player.dispose();
            }
        }
    }));
</script>
@endscript
