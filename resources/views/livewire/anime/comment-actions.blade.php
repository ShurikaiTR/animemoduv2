<?php

declare(strict_types=1);

use App\Actions\Comment\ToggleCommentLikeAction;
use App\Actions\Review\ToggleReviewHelpfulAction;
use App\Models\Comment;
use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Isolate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Component;

new #[Isolate]
    class extends Component {
    /** @var Comment|Review */
    #[Locked]
    public Model $comment;

    public string $activeTab;

    public int $likeCount = 0;

    public int $dislikeCount = 0;

    public bool $isLiked = false;

    public bool $isDisliked = false;

    public bool $isHelpful = false;

    public function mount(Model $comment, string $activeTab): void
    {
        $this->comment = $comment;
        $this->activeTab = $activeTab;
        $this->updateState();
    }

    #[Renderless]
    public function toggleLike(ToggleCommentLikeAction $action, bool $isLike): void
    {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');
            return;
        }

        if (!$this->comment instanceof Comment) {
            return;
        }

        $result = $action->execute(Auth::id(), (string) $this->comment->id, $isLike);
        $this->likeCount = $result['like_count'];
        $this->dislikeCount = $result['dislike_count'];
        $this->isLiked = $result['user_status'] === true;
        $this->isDisliked = $result['user_status'] === false;
    }

    #[Renderless]
    public function toggleHelpful(ToggleReviewHelpfulAction $action): void
    {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');
            return;
        }

        if (!$this->comment instanceof Review) {
            return;
        }

        $result = $action->execute(Auth::id(), (string) $this->comment->id);
        $this->likeCount = $result['helpful_count'];
        $this->isHelpful = $result['is_helpful'];
    }

    private function updateState(): void
    {
        if ($this->comment instanceof Comment) {
            $this->likeCount = (int) ($this->comment->like_count ?? 0);
            $this->dislikeCount = (int) ($this->comment->dislike_count ?? 0);

            if (Auth::check()) {
                $userLike = $this->comment->likes()->where('user_id', Auth::id())->first();
                $this->isLiked = $userLike && $userLike->is_like;
                $this->isDisliked = $userLike && !$userLike->is_like;
            }
        } elseif ($this->comment instanceof Review) {
            $this->likeCount = (int) ($this->comment->helpful_count ?? 0);
            $this->isHelpful = Auth::check() && $this->comment->helpfulVotes()->where('user_id', Auth::id())->exists();
        }
    }
}; ?>

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
    @if($this->activeTab === 'comments')
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