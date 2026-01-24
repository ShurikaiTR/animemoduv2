<div x-data="{ 
    likeCount: @entangle('likeCount').live,
    dislikeCount: @entangle('dislikeCount').live,
    isLiked: @entangle('isLiked').live,
    isDisliked: @entangle('isDisliked').live,
    isHelpful: @entangle('isHelpful').live,
    toggleLike(status) {
        if (!{{ Auth::check() ? 'true' : 'false' }}) {
            $wire.dispatch('openAuthModal');
            return;
        }

        if (status === true) {
            if (this.isLiked) {
                this.likeCount--;
                this.isLiked = false;
            } else {
                this.likeCount++;
                if (this.isDisliked) {
                    this.dislikeCount--;
                    this.isDisliked = false;
                }
                this.isLiked = true;
            }
        } else {
            if (this.isDisliked) {
                this.dislikeCount--;
                this.isDisliked = false;
            } else {
                this.dislikeCount++;
                if (this.isLiked) {
                    this.likeCount--;
                    this.isLiked = false;
                }
                this.isDisliked = true;
            }
        }
        $wire.toggleLike(status);
    },
    toggleHelpful() {
        if (!{{ Auth::check() ? 'true' : 'false' }}) {
            $wire.dispatch('openAuthModal');
            return;
        }

        if (this.isHelpful) {
            this.likeCount--;
            this.isHelpful = false;
        } else {
            this.likeCount++;
            this.isHelpful = true;
        }
        $wire.toggleHelpful();
    }
}">
    @if($activeTab === 'comments')
        <div
            class="flex items-center bg-white/5 rounded-full p-1 ring-1 ring-white/5 group-hover/comment:ring-white/10 transition-all">
            <button @click="toggleLike(true)"
                class="px-3 py-1.5 flex items-center gap-2 rounded-full hover:bg-primary/10 transition-all"
                :class="isLiked ? 'text-primary bg-primary/10' : 'text-white/40 hover:text-primary'">
                <x-heroicon-s-hand-thumb-up class="w-4 h-4" />
                <span class="text-xs font-bold" x-text="likeCount"></span>
            </button>
            <div class="w-px h-4 bg-white/10 mx-0.5"></div>
            <button @click="toggleLike(false)"
                class="px-3 py-1.5 flex items-center gap-2 rounded-full hover:bg-red-500/10 transition-all"
                :class="isDisliked ? 'text-red-500 bg-red-500/10' : 'text-white/40 hover:text-red-500'">
                <x-heroicon-s-hand-thumb-down class="w-4 h-4" />
                <span class="text-xs font-bold" x-text="dislikeCount"></span>
            </button>
        </div>
    @else
        <button @click="toggleHelpful" class="flex items-center gap-3 transition-all group/helpful"
            :class="isHelpful ? 'text-primary' : 'text-white/40 hover:text-primary'">
            <div class="p-2 rounded-xl transition-colors border"
                :class="isHelpful ? 'bg-primary/10 border-primary/20' : 'bg-white/5 border-white/5 group-hover/helpful:bg-primary/10 group-hover/helpful:border-primary/20'">
                <x-heroicon-s-hand-thumb-up class="w-4 h-4" />
            </div>
            <span class="text-sm font-medium tracking-tight">
                <span x-text="likeCount"></span> kişi bu incelemeyi yararlı buldu
            </span>
        </button>
    @endif
</div>