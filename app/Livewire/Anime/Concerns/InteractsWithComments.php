<?php

declare(strict_types=1);

namespace App\Livewire\Anime\Concerns;

use App\Actions\CreateCommentAction;
use App\Actions\CreateReviewAction;
use App\Actions\PinCommentAction;
use App\Actions\ToggleCommentLikeAction;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

trait InteractsWithComments
{
    public function submit(?string $parentId = null): void
    {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');

            return;
        }

        if ($this->activeTab === 'comments') {
            $this->handleCommentSubmission($parentId);
        } else {
            $this->handleReviewSubmission();
        }
    }

    private function handleCommentSubmission(?string $parentId): void
    {
        $this->validate([
            'content' => $parentId ? 'nullable' : 'required|string|min:3|max:1000',
            'replyContent.' . $parentId => $parentId ? 'required|string|min:3|max:1000' : 'nullable',
            'isSpoiler' => 'boolean',
        ]);

        app(CreateCommentAction::class)->execute([
            'anime_id' => $this->anime->id,
            'episode_id' => $this->episode?->id,
            'parent_id' => $parentId,
            'content' => $parentId ? $this->replyContent[$parentId] : $this->content,
            'is_spoiler' => $this->isSpoiler,
        ]);

        if ($parentId) {
            unset($this->replyContent[$parentId]);
        } else {
            $this->content = '';
        }

        $this->showReplyInput = [];
        $this->dispatch('comment-added');
        session()->flash('toast', ['type' => 'success', 'message' => 'Yorumun gönderildi!']);
        $this->resetFields();
    }

    private function handleReviewSubmission(): void
    {
        $this->validate([
            'content' => 'required|string|min:10|max:2000',
            'title' => 'nullable|string|max:100',
            'rating' => 'required|integer|min:1|max:10',
            'isSpoiler' => 'boolean',
        ]);

        app(CreateReviewAction::class)->execute([
            'anime_id' => $this->anime->id,
            'title' => $this->title,
            'content' => $this->content,
            'rating' => $this->rating,
            'is_spoiler' => $this->isSpoiler,
        ]);

        $this->dispatch('comment-added');
        session()->flash('toast', ['type' => 'success', 'message' => 'İncelemen paylaşıldı!']);
        $this->resetFields();
    }

    public function toggleReply(string $commentId): void
    {
        $this->showReplyInput[$commentId] = !($this->showReplyInput[$commentId] ?? false);
    }

    public function revealSpoiler(string $commentId): void
    {
        $this->revealedSpoilers[$commentId] = true;
    }

    public function pinComment(string $commentId): void
    {
        try {
            $comment = app(PinCommentAction::class)->execute($commentId);
            session()->flash('toast', ['type' => 'success', 'message' => $comment->is_pinned ? 'Sabitlendi!' : 'Kaldırıldı!']);
        } catch (\Exception $e) {
        }
    }

    public function toggleLike(string $commentId, bool $isLike): void
    {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');

            return;
        }

        try {
            app(ToggleCommentLikeAction::class)->execute(Auth::id(), $commentId, $isLike);
        } catch (\Exception $e) {
        }
    }

    public function quote(string $commentId): void
    {
        $comment = Comment::find($commentId);
        if (!$comment) {
            return;
        }

        $this->showReplyInput[$commentId] = true;
        $this->replyContent[$commentId] = "[quote]{$comment->content}[/quote]\n\n";
    }

    private function resetFields(): void
    {
        $this->content = '';
        $this->isSpoiler = false;
        $this->title = '';
        $this->rating = 0;
    }
}
