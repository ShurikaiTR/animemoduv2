<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use App\Models\Comment;
use App\Models\Episode;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;



class Comments extends Component
{
    public Anime $anime;
    public ?Episode $episode = null;

    public string $activeTab = 'comments'; // 'comments', 'reviews'
    public string $content = '';
    public string $title = '';
    public int $rating = 0;
    public bool $isSpoiler = false;

    /** @var array<string, string> */
    public array $replyContent = [];

    public int $perPage = 10;
    public bool $hasMore = false;

    /** @var array<string, bool> */
    public array $showReplyInput = [];

    /** @var array<string, bool> */
    public array $revealedSpoilers = [];

    use Concerns\InteractsWithComments;

    public function mount(Anime $anime, ?Episode $episode = null): void
    {
        $this->anime = $anime;
        $this->episode = $episode;

        if ($this->episode?->exists) {
            $this->activeTab = 'comments';
        }
    }

    public function setTab(string $tab): void
    {
        if ($this->episode?->exists && $tab === 'reviews') {
            return;
        }

        $this->activeTab = $tab;
        $this->perPage = 10;
        $this->resetValidation();
        $this->resetFields();
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function render(): View
    {
        $service = app(\App\Services\CommentService::class);
        $counts = $service->getCounts($this->anime->id, $this->episode?->id);
        $items = $service->getItems(['anime_id' => $this->anime->id, 'episode_id' => $this->episode?->id, 'perPage' => $this->perPage, 'activeTab' => $this->activeTab]);
        $this->hasMore = $items->count() < ($this->activeTab === 'comments' ? $counts['comments'] : $counts['reviews']);

        return view('livewire.anime.comments', [
            'items' => $items,
            'commentsCount' => $counts['comments'],
            'reviewsCount' => $counts['reviews'],
            'showReviews' => empty($this->episode?->id)
        ]);
    }
}
