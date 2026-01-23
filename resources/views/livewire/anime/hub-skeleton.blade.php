<div class="min-h-screen pb-20 bg-bg-main">
    <div class="container mx-auto px-4 md:px-6 pt-24 md:pt-32 pb-10 font-rubik">
        {{-- A-Z Filter Bar Skeleton --}}
        <div class="mb-8 overflow-x-auto pb-2 scrollbar-hide">
            <div class="flex items-center gap-1 min-w-max">
                <x-ui.skeleton width="w-16" height="h-9" variant="text" />
                <div class="w-px h-6 bg-white/10 mx-2"></div>
                @foreach(range(1, 15) as $i)
                    <x-ui.skeleton width="w-9" height="h-9" variant="text" />
                @endforeach
            </div>
        </div>

        {{-- Grid Content Skeleton --}}
        <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <x-ui.skeleton width="w-64" height="h-8" class="mb-2" />
                    <x-ui.skeleton width="w-48" height="h-4" />
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach(range(1, 24) as $i)
                    <div class="aspect-[2/3] w-full">
                        <x-ui.skeleton width="w-full" height="h-full" variant="rect" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>