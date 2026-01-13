@props(['characters'])

@if($characters && count($characters) > 0)
    <div class="mb-12 relative group/list" x-data="{ 
                    scroll(direction) {
                        const container = $refs.castList;
                        const amount = direction === 'left' ? -320 : 320;
                        container.scrollBy({ left: amount, behavior: 'smooth' });
                    }
                }">
        <div class="flex items-center justify-between mb-6">
            <h3 class="flex items-center gap-3 text-2xl text-white font-rubik font-normal">
                <x-icons.users class="w-6 h-6 text-primary" />
                Karakterler
            </h3>

            <div class="hidden md:flex items-center gap-2">
                <x-anime.scroll-button direction="left" @click="scroll('left')" />
                <x-anime.scroll-button direction="right" @click="scroll('right')" />
            </div>
        </div>

        <div x-ref="castList"
            class="flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 no-scrollbar snap-x snap-mandatory scroll-smooth">
            @foreach($characters as $character)
                <div class="min-w-[45%] sm:min-w-[35%] md:min-w-[10rem] snap-start">
                    <x-anime.character-card :name="$character['name']" :character="$character['role'] === 'MAIN' ? 'Ana Karakter' : ($character['role'] === 'SUPPORTING' ? 'Yan Karakter' : 'Karakter')" :image="$character['image']" />
                </div>
            @endforeach
        </div>
    </div>
@endif