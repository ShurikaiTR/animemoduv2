<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Support\Facades\DB;

class ToggleCommentLikeAction
{
    /**
     * Toggles a like or dislike on a comment.
     *
     * @param string $userId
     * @param string $commentId
     * @param bool $isLike True for like, false for dislike
     * @return array{like_count: int, dislike_count: int, user_status: ?bool} Returns updated counts and user's new status (true=like, false=dislike, null=none)
     */
    public function execute(string $userId, string $commentId, bool $isLike): array
    {
        return DB::transaction(function () use ($userId, $commentId, $isLike) {
            $existing = CommentLike::where('user_id', $userId)
                ->where('comment_id', $commentId)
                ->first();

            $comment = Comment::lockForUpdate()->find($commentId);

            if ($existing) {
                if ($existing->is_like === $isLike) {
                    // Toggle off (remove)
                    $existing->delete();
                    if ($isLike) {
                        $comment->decrement('like_count');
                    } else {
                        $comment->decrement('dislike_count');
                    }
                    $userStatus = null;
                } else {
                    // Switch (like -> dislike or vice versa)
                    $existing->update(['is_like' => $isLike]);
                    if ($isLike) {
                        $comment->increment('like_count');
                        $comment->decrement('dislike_count');
                    } else {
                        $comment->decrement('like_count');
                        $comment->increment('dislike_count');
                    }
                    $userStatus = $isLike;
                }
            } else {
                // New interaction
                CommentLike::create([
                    'user_id' => $userId,
                    'comment_id' => $commentId,
                    'is_like' => $isLike,
                ]);

                if ($isLike) {
                    $comment->increment('like_count');
                } else {
                    $comment->increment('dislike_count');
                }
                $userStatus = $isLike;
            }

            // Refresh to get exact counts if needed, but increment/decrement is safer for concurrency.
            // However, we return the values.
            $comment->refresh();

            return [
                'like_count' => $comment->like_count,
                'dislike_count' => $comment->dislike_count,
                'user_status' => $userStatus,
            ];
        });
    }
}
