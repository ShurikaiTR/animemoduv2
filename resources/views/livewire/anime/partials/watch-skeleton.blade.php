<div class="pt-24 pb-16 min-h-screen bg-bg-main animate-pulse">
    <div class="container mx-auto px-4 md:px-6">
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_22rem] gap-8">

            {{-- Left Column --}}
            <div class="space-y-8">
                {{-- Video Player Skeleton --}}
                <div class="w-full aspect-video bg-white/5 rounded-2xl border border-white/5"></div>

                {{-- Navigation Skeleton --}}
                <div class="h-16 bg-white/5 rounded-xl border border-white/5"></div>

                {{-- Comments Skeleton --}}
                <div class="space-y-4">
                    <div class="h-8 w-40 bg-white/5 rounded"></div>
                    <div class="space-y-4">
                        @foreach(range(1, 3) as $i)
                            <div class="flex gap-4">
                                <div class="w-12 h-12 rounded-full bg-white/5 shrink-0"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 w-32 bg-white/5 rounded"></div>
                                    <div class="h-16 w-full bg-white/5 rounded"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column (Sidebar) --}}
            <div class="hidden xl:block">
                <div class="space-y-4">
                    <div class="h-8 w-full bg-white/5 rounded"></div>
                    <div class="space-y-2">
                        @foreach(range(1, 10) as $i)
                            <div class="h-14 w-full bg-white/5 rounded-lg"></div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>