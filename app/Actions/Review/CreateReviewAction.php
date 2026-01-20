<?php

declare(strict_types=1);

namespace App\Actions\Review;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class CreateReviewAction
{
    /**
     * @param array{
     *     anime_id: string,
     *     title: ?string,
     *     content: string,
     *     rating: int,
     *     is_spoiler: bool
     * } $data
     */
    public function execute(array $data): Review
    {
        return Review::create([
            'user_id' => Auth::id(),
            'anime_id' => $data['anime_id'],
            'title' => $data['title'],
            'content' => $data['content'],
            'rating' => $data['rating'],
            'is_spoiler' => $data['is_spoiler'],
        ]);
    }
}
