<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CommentTab;
use App\Models\Comment;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    /**
     * @param array{
     *     anime_id: string,
     *     episode_id: ?string,
     *     perPage: int,
     *     activeTab: string
     * } $params
     * @return Collection<int, Comment|Review>
     */
    public function getItems(array $params): Collection
    {
        $userId = auth()->id();

        if ($params['activeTab'] === CommentTab::COMMENTS->value) {
            $query = Comment::with([
                'user.profile',
                'replies.user.profile',
                'replies.likes' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                },
                'likes' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                },
            ])
                ->whereNull('parent_id')
                ->where('anime_id', $params['anime_id'])
                ->where('episode_id', $params['episode_id']);
        } else {
            $query = Review::with(['user.profile'])->where('anime_id', $params['anime_id']);
        }

        return $query->orderByDesc('is_pinned')->latest()->limit($params['perPage'])->get();
    }

    public function getCounts(string $animeId, ?string $episodeId): array
    {
        return [
            'comments' => Comment::where('anime_id', $animeId)->where('episode_id', $episodeId)->whereNull('parent_id')->count(),
            'reviews' => Review::where('anime_id', $animeId)->count(),
        ];
    }
}
