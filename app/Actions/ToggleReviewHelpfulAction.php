<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Review;
use App\Models\ReviewHelpful;
use Illuminate\Support\Facades\DB;

class ToggleReviewHelpfulAction
{
    /**
     * Toggles a helpful vote on a review.
     *
     * @return array{helpful_count: int, is_helpful: bool}
     */
    public function execute(string $userId, string $reviewId): array
    {
        return DB::transaction(function () use ($userId, $reviewId) {
            $existing = ReviewHelpful::where('user_id', $userId)
                ->where('review_id', $reviewId)
                ->first();

            $review = Review::lockForUpdate()->find($reviewId);

            if ($existing) {
                // Toggle off
                $existing->delete();
                $review->decrement('helpful_count');
                $isHelpful = false;
            } else {
                // Toggle on
                ReviewHelpful::create([
                    'user_id' => $userId,
                    'review_id' => $reviewId,
                ]);
                $review->increment('helpful_count');
                $isHelpful = true;
            }

            $review->refresh();

            return [
                'helpful_count' => $review->helpful_count,
                'is_helpful' => $isHelpful,
            ];
        });
    }
}
