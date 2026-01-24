<?php

declare(strict_types=1);

namespace App\Livewire\Anime\Concerns;

use App\Actions\Comment\CreateCommentAction;
use App\Actions\Review\CreateReviewAction;
use App\Actions\Comment\PinCommentAction;
use App\Actions\Comment\ToggleCommentLikeAction;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

trait InteractsWithComments
{
    public function submit(
        CreateCommentAction $commentAction,
        CreateReviewAction $reviewAction,
        ?string $parentId = null
    ): void {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');
            return;
        }

        if ($this->activeTab === 'comments') {
            $this->handleCommentSubmission($commentAction, $parentId);
        } else {
            $this->handleReviewSubmission($reviewAction);
        }
    }

    private function handleCommentSubmission(CreateCommentAction $action, ?string $parentId): void
    {
        if ($parentId) {
            $this->validate(['replyContent.' . $parentId => 'required|string|min:3|max:1000']);
        } else {
            $this->commentForm->validate();
        }

        $action->execute([
            'anime_id' => $this->anime->id,
            'episode_id' => $this->episode?->id,
            'parent_id' => $parentId,
            'content' => $parentId ? $this->replyContent[$parentId] : $this->commentForm->content,
            'is_spoiler' => $parentId ? false : $this->commentForm->isSpoiler,
        ]);

        if ($parentId) {
            unset($this->replyContent[$parentId]);
        } else {
            $this->commentForm->resetFields();
        }

        $this->showReplyInput = [];
        $this->dispatch('comment-added');
        session()->flash('toast', ['type' => 'success', 'message' => 'Yorumun gönderildi!']);
    }

    private function handleReviewSubmission(CreateReviewAction $action): void
    {
        $this->reviewForm->validate();

        $action->execute([
            'anime_id' => $this->anime->id,
            'title' => $this->reviewForm->title,
            'content' => $this->reviewForm->content,
            'rating' => $this->reviewForm->rating,
            'is_spoiler' => $this->reviewForm->isSpoiler,
        ]);

        $this->dispatch('comment-added');
        $this->reviewForm->resetFields();
        session()->flash('toast', ['type' => 'success', 'message' => 'İncelemen paylaşıldı!']);
    }

    public function toggleReply(string $commentId): void
    {
        $this->showReplyInput[$commentId] = !($this->showReplyInput[$commentId] ?? false);
    }

    public function revealSpoiler(string $commentId): void
    {
        $this->revealedSpoilers[$commentId] = true;
    }

    public function pinComment(PinCommentAction $action, string $commentId): void
    {
        try {
            $comment = $action->execute($commentId);
            session()->flash('toast', ['type' => 'success', 'message' => $comment->is_pinned ? 'Sabitlendi!' : 'Kaldırıldı!']);
        } catch (\Exception $e) {
        }
    }

    public function toggleLike(ToggleCommentLikeAction $action, string $commentId, bool $isLike): void
    {
        if (!Auth::check()) {
            $this->dispatch('openAuthModal');
            return;
        }

        try {
            $action->execute(Auth::id(), $commentId, $isLike);
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
}
