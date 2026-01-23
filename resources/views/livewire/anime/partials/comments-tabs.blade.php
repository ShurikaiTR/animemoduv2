@props(['activeTab', 'commentsCount', 'reviewsCount', 'showReviews'])

<ul class="flex flex-row items-start gap-8" wire:key="comments-tabs">
    <li>
        <button wire:click="setTab('comments')" wire:key="tab-comments"
            class="flex items-start gap-2.5 transition-opacity duration-300 {{ $activeTab === 'comments' ? 'opacity-100' : 'opacity-50 hover:opacity-100' }}">
            <h4 class="text-white font-bold font-rubik text-2xl leading-none">Yorumlar</h4>
            <span
                class="flex items-center justify-center min-w-5 h-5 px-1.5 rounded-md bg-primary text-xs text-white/80 font-bold">
                {{ $commentsCount }}
            </span>
        </button>
    </li>
    @if($showReviews)
        <li>
            <button wire:click="setTab('reviews')" wire:key="tab-reviews"
                class="flex items-start gap-2.5 transition-opacity duration-300 {{ $activeTab === 'reviews' ? 'opacity-100' : 'opacity-50 hover:opacity-100' }}">
                <h4 class="text-white font-bold font-rubik text-2xl leading-none">İncelemeler</h4>
                <span
                    class="flex items-center justify-center min-w-5 h-5 px-1.5 rounded-md bg-primary text-xs text-white/80 font-bold">
                    {{ $reviewsCount }}
                </span>
            </button>
        </li>
    @endif
</ul>