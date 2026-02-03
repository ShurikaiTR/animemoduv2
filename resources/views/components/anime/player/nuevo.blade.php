@props([
    'src' => null,
    'backdrop' => null,
    'poster' => null,
    'anime' => null,
    'episode' => null,
    'logo' => null
])

<div 
    x-data="videoPlayer({
        src: '{{ $src }}',
        backdrop: '{{ $backdrop }}',
        poster: '{{ $poster }}',
        logo: '{{ $logo }}',
        animeTitle: {{ json_encode($anime?->title ?? '') }},
        episodeTitle: '{{ $episode?->season_number }}. Sezon {{ $episode?->episode_number }}. Bölüm'
    })"
    class="w-full h-full rounded-xl overflow-hidden bg-black relative z-10 group"
>
    {{-- Background Backdrop (Anime Genel Görseli) --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <template x-if="backdrop">
            <div class="absolute inset-0 w-full h-full">
                <img :src="backdrop" class="absolute inset-0 w-full h-full object-cover opacity-30 blur-[2px] transition-all duration-700 scale-105" />
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-black/40"></div>
            </div>
        </template>
    </div>

    {{-- Loading State --}}
    <template x-if="!isReady">
        <div class="w-full h-full flex items-center justify-center bg-transparent absolute inset-0 z-50">
            <div class="w-16 h-16 border-4 border-white/20 border-t-primary rounded-full animate-spin"></div>
        </div>
    </template>

    {{-- Player Header Overlay --}}
    <div 
        x-show="!isReady || showOverlay" 
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        class="absolute top-6 left-6 z-40 flex items-center gap-4 pointer-events-none select-none"
    >
        <template x-if="logo">
            <div class="relative">
                <div class="absolute inset-0 rounded-full bg-primary/40 animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite] opacity-50"></div>
                <div class="relative w-14 h-14 rounded-full overflow-hidden border-2 border-white/10 shadow-xl bg-black/50 backdrop-blur-md z-11">
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
    <div x-show="isReady" class="w-full h-full relative z-10">
        <video
            x-ref="video"
            class="video-js vjs-big-play-centered"
            style="width: 100%; height: 100%;"
            playsinline
            preload="auto"
            crossorigin="anonymous"
        ></video>
    </div>
</div>

@assets
<script src="/player/video.min.js"></script>
<script src="/player/nuevo.min.js"></script>
<link href="/player/skins/flow/videojs.min.css" rel="stylesheet">
<style>
    .video-js .vjs-poster { display: none !important; }
    .video-js { background-color: transparent !important; }
    .video-js video { object-fit: contain; }
</style>
@endassets

@script
<script>
    Alpine.data('videoPlayer', (config) => ({
        player: null,
        isReady: false,
        showOverlay: true,
        
        // Root-level properties for reactivity
        src: config.src,
        backdrop: config.backdrop,
        poster: config.poster,
        logo: config.logo,
        animeTitle: config.animeTitle,
        episodeTitle: config.episodeTitle,
        
        init() {
            this.$nextTick(() => {
                this.loadLanguages().then(() => {
                    this.initPlayer();
                });
            });

            Livewire.on('play-episode', (data) => this.handleEpisodeChange(data));
        },

        getVideoType(url) {
            if (!url) return 'video/mp4';
            if (url.includes('.m3u8') || url.includes('hls')) return 'application/x-mpegURL';
            if (url.includes('.mpd')) return 'application/dash+xml';
            return 'video/mp4';
        },

        handleEpisodeChange(data) {
            this.animeTitle = data.anime_title;
            this.episodeTitle = data.episode_title;
            this.logo = data.logo;
            this.backdrop = data.backdrop || this.backdrop;
            this.poster = data.poster;
            this.src = data.src;

            if (this.player) {
                this.player.src({ 
                    type: this.getVideoType(data.src), 
                    src: data.src 
                });

                const titleEl = this.player.el().querySelector('.vjs-nuevo-title');
                if(titleEl) titleEl.innerHTML = data.anime_title;

                if(data.force_play) {
                    this.player.play();
                } else {
                    this.showOverlay = true;
                }
            }
        },

        async loadLanguages() {
            let checkCount = 0;
            while(!window.videojs && checkCount < 50) {
                await new Promise(r => setTimeout(r, 100));
                checkCount++;
            }

            if(window.videojs && (!window.videojs.languages || !window.videojs.languages['tr'])) {
                if (!document.querySelector('script[src*="tr.js"]')) {
                    const script = document.createElement('script');
                    script.src = '/player/lang/tr.js';
                    document.body.appendChild(script);
                    await new Promise(r => script.onload = r);
                }
            }
        },

        initPlayer() {
            if (!window.videojs) return;

            if (this.player) {
                if (this.player.src() !== this.src) {
                    this.player.src({ type: this.getVideoType(this.src), src: this.src });
                }
                this.isReady = true;
                return;
            }

            this.player = videojs(this.$refs.video, {
                controls: true,
                autoplay: false,
                preload: 'auto',
                fluid: true,
                sources: [{
                    src: this.src,
                    type: this.getVideoType(this.src)
                }],
                language: 'tr',
                html5: {
                    hls: {
                        enableLowInitialPlaylist: true,
                        smoothQualityChange: true,
                        overrideNative: true
                    }
                }
            });

            this.player.ready(() => {
                this.isReady = true;

                this.player.on('play', () => { this.showOverlay = false; });
                this.player.on('pause', () => { this.showOverlay = true; });
                this.player.on('ended', () => { this.showOverlay = true; });

                if (this.player.nuevo) {
                    this.player.nuevo({
                        skin: 'flow',
                        title: this.animeTitle,
                        settingsButton: true,
                        touchControls: true
                    });
                }
            });
        },

        destroy() {
            if (this.player) {
                this.player.dispose();
                this.player = null;
            }
        }
    }));
</script>
@endscript
