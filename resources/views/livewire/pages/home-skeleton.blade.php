<div class="pt-20 lg:pt-24 pb-8 animate-pulse">
    {{-- Hero Slider Skeleton --}}
    <div class="mb-12 md:max-w-7xl md:mx-auto md:px-8">
        <div
            class="relative w-full h-128 md:h-144 lg:h-160 min-h-128 overflow-hidden bg-white/5 rounded-none md:rounded-3xl border border-white/10">
            <div class="absolute inset-x-0 bottom-0 p-4 md:p-12 lg:p-16 space-y-6">
                {{-- Logo/Title Skeleton --}}
                <x-ui.skeleton width="w-48 md:w-64" height="h-12 md:h-16" />

                {{-- Meta Skeleton --}}
                <div class="flex gap-3">
                    <x-ui.skeleton width="w-12" height="h-12" variant="circle" />
                    <x-ui.skeleton width="w-20" height="h-6" class="mt-3" />
                    <x-ui.skeleton width="w-16" height="h-6" class="mt-3" />
                </div>

                {{-- Description Skeleton --}}
                <div class="space-y-2">
                    <x-ui.skeleton width="w-full max-w-2xl" height="h-4" />
                    <x-ui.skeleton width="w-full max-w-xl" height="h-4" />
                    <x-ui.skeleton width="w-full max-w-lg" height="h-4" />
                </div>

                {{-- Buttons Skeleton --}}
                <div class="flex gap-4 mt-4">
                    <x-ui.skeleton width="w-40" height="h-14" />
                    <x-ui.skeleton width="w-14" height="h-14" class="rounded-2xl" />
                </div>
            </div>
        </div>
    </div>

    <x-layout.container class="!px-4 sm:!px-8 mb-12">
        <div class="space-y-12">
            {{-- Latest Episodes Skeleton --}}
            <section class="pb-8 pt-4">
                <div class="flex items-center justify-between mb-6">
                    <x-ui.skeleton width="w-48" height="h-8" />
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                    @foreach(range(1, 5) as $i)
                        <div class="space-y-3">
                            <x-ui.skeleton width="w-full" height="h-40 md:h-48" class="rounded-2xl" />
                            <x-ui.skeleton width="w-3/4" height="h-4" />
                            <x-ui.skeleton width="w-1/2" height="h-3" />
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Recent Animes Skeleton --}}
            <section class="pb-12 pt-4">
                <div class="flex items-center justify-between mb-8">
                    <x-ui.skeleton width="w-40" height="h-8" />
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 sm:gap-8">
                    @foreach(range(1, 5) as $i)
                        <div class="space-y-4">
                            <x-ui.skeleton width="w-full" height="aspect-poster" class="rounded-2xl md:rounded-3xl" />
                            <div class="space-y-2">
                                <x-ui.skeleton width="w-3/4" height="h-5" />
                                <x-ui.skeleton width="w-1/2" height="h-4" />
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </x-layout.container>
</div>