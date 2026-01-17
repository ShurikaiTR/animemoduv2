<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Actions\ToggleCommentLikeAction;
use App\Actions\ToggleReviewHelpfulAction;
use App\Models\Comment;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Isolate;
use Livewire\Component;

#[Isolate]
class CommentActions extends Component
{
    /** @var Comment|Review */
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

    public function toggleLike(bool $isLike): void
    {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');
            return;
        }

        if (!$this->comment instanceof Comment) {
            return;
        }

        $result = app(ToggleCommentLikeAction::class)->execute(Auth::id(), (string) $this->comment->id, $isLike);
        $this->likeCount = $result['like_count'];
        $this->dislikeCount = $result['dislike_count'];
        $this->isLiked = $result['user_status'] === true;
        $this->isDisliked = $result['user_status'] === false;
    }

    public function toggleHelpful(): void
    {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');
            return;
        }

        if (!$this->comment instanceof Review) {
            return;
        }

        $result = app(ToggleReviewHelpfulAction::class)->execute(Auth::id(), (string) $this->comment->id);
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

    public function render(): View
    {
        return view('livewire.anime.comment-actions');
    }
}
