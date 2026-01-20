<?php

declare(strict_types=1);

namespace App\Actions\Comment;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CreateCommentAction
{
    /**
     * @param array{
     *     anime_id: string,
     *     episode_id: ?string,
     *     parent_id: ?string,
     *     content: string,
     *     is_spoiler: bool
     * } $data
     */
    public function execute(array $data): Comment
    {
        return Comment::create([
            'user_id' => Auth::id(),
            'anime_id' => $data['anime_id'],
            'episode_id' => $data['episode_id'],
            'parent_id' => $data['parent_id'],
            'content' => $data['content'],
            'is_spoiler' => $data['is_spoiler'],
        ]);
    }
}
