<div class="animate-pulse">
    {{-- Hero Skeleton --}}
    <section class="relative pb-16 pt-60 -mt-24 bg-bg-main overflow-hidden">
        {{-- Backdrop Skeleton --}}
        <div class="absolute top-0 left-0 right-0 h-96 w-full z-0 bg-white/5"></div>

        <x-layout.container class="z-20 relative">
            <div class="flex flex-col xl:flex-row gap-8">
                <div class="flex-1">
                    {{-- Title --}}
                    <x-ui.skeleton width="w-3/4 md:w-1/2" height="h-12" class="mb-6" />

                    {{-- Metadata --}}
                    <div class="flex items-center gap-4 mb-8">
                        <x-ui.skeleton width="w-16" height="h-6" variant="text" />
                        <x-ui.skeleton width="w-24" height="h-6" variant="text" />
                        <x-ui.skeleton width="w-20" height="h-6" variant="text" />
                    </div>

                    {{-- Description Line 1 --}}
                    <x-ui.skeleton width="w-full" height="h-4" variant="text" class="mb-2" />
                    {{-- Description Line 2 --}}
                    <x-ui.skeleton width="w-full" height="h-4" variant="text" class="mb-2" />
                    {{-- Description Line 3 --}}
                    <x-ui.skeleton width="w-2/3" height="h-4" variant="text" class="mb-10" />

                    {{-- Actions --}}
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <x-ui.skeleton width="w-full md:w-40" height="h-12" />
                        <div class="flex items-center gap-4">
                            <x-ui.skeleton width="w-12" height="h-12" variant="circle" />
                            <x-ui.skeleton width="w-12" height="h-12" variant="circle" />
                        </div>
                    </div>
                </div>
            </div>
        </x-layout.container>
    </section>

    {{-- Content Skeleton --}}
    <section class="pb-16 pt-8 bg-bg-main">
        <x-layout.container>
            <div class="space-y-12">
                {{-- Episode List Skeleton --}}
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <x-ui.skeleton width="w-48" height="h-8" />
                    </div>
                    <div class="flex gap-4 overflow-hidden">
                        @foreach(range(1, 4) as $i)
                            <div class="min-w-[85%] md:min-w-[20rem]">
                                <x-ui.skeleton width="w-full" height="h-48" class="rounded-2xl" />
                                <div class="mt-4 space-y-2">
                                    <x-ui.skeleton width="w-1/2" height="h-4" variant="text" />
                                    <x-ui.skeleton width="w-1/3" height="h-3" variant="text" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Cast List Skeleton --}}
                <div>
                    <x-ui.skeleton width="w-40" height="h-8" class="mb-6" />
                    <div class="flex gap-4 overflow-hidden">
                        @foreach(range(1, 6) as $i)
                            <x-ui.skeleton width="w-32" height="h-48" class="rounded-2xl" />
                        @endforeach
                    </div>
                </div>
            </div>
        </x-layout.container>
    </section>
</div>