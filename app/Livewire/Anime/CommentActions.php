<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Actions\ToggleCommentLikeAction;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class CommentActions extends Component
{
    public Comment $comment;

    public string $activeTab;

    public int $likeCount = 0;

    public int $dislikeCount = 0;

    public bool $isLiked = false;

    public bool $isDisliked = false;

    public function mount(Comment $comment, string $activeTab): void
    {
        $this->comment = $comment;
        $this->activeTab = $activeTab;
        $this->updateState();
    }

    public function toggleLike(bool $isLike): void
    {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');

            return;
        }

        try {
            app(ToggleCommentLikeAction::class)->execute(Auth::id(), (string) $this->comment->id, $isLike);
            $this->comment->refresh();
            $this->updateState();
        } catch (\Exception $e) {
            // Error handling
        }
    }

    private function updateState(): void
    {
        $this->likeCount = (int) ($this->comment->like_count ?? 0);
        $this->dislikeCount = (int) ($this->comment->dislike_count ?? 0);

        if (Auth::check()) {
            $userLike = $this->comment->likes()->where('user_id', Auth::id())->first();
            $this->isLiked = $userLike && $userLike->is_like;
            $this->isDisliked = $userLike && !$userLike->is_like;
        }
    }

    public function render(): View
    {
        return view('livewire.anime.comment-actions');
    }
}
