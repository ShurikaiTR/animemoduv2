<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class PinCommentAction
{
    public function execute(string $commentId): Comment
    {
        if (Auth::user()?->profile?->role !== 'admin') {
            throw new \Exception('Unauthorized action.');
        }

        $comment = Comment::findOrFail($commentId);
        $comment->update(['is_pinned' => !$comment->is_pinned]);

        return $comment;
    }
}
