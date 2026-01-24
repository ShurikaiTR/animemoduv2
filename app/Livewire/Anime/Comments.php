<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Enums\CommentTab;
use App\Models\Anime;
use App\Models\Episode;
use App\Services\CommentService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component as BaseComponent;
use App\Livewire\Forms\CommentForm;
use App\Livewire\Forms\ReviewForm;

class Comments extends BaseComponent
{
    public function placeholder(): View
    {
        return view('livewire.anime.partials.comments-skeleton');
    }

    #[On('comment-added')]
    public function refresh(): void
    {
    }

    #[Locked]
    public Anime $anime;

    #[Locked]
    public ?Episode $episode = null;

    public string $activeTab = CommentTab::COMMENTS->value;

    public CommentForm $commentForm;

    public ReviewForm $reviewForm;

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
            $this->activeTab = CommentTab::COMMENTS->value;
        }
    }

    public function setTab(string $tab): void
    {
        if ($this->episode?->exists && $tab === CommentTab::REVIEWS->value) {
            return;
        }

        $this->activeTab = $tab;
        $this->perPage = 10;
        $this->resetValidation();
        $this->commentForm->resetFields();
        $this->reviewForm->resetFields();
        $this->showReplyInput = [];
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    #[Computed]
    public function hasMore(): bool
    {
        $total = ($this->activeTab === CommentTab::COMMENTS->value ? $this->counts['comments'] : $this->counts['reviews']);
        return $this->items->count() < $total;
    }

    #[Computed]
    public function showReviews(): bool
    {
        return empty($this->episode?->id);
    }

    public function render(): View
    {
        return view('livewire.anime.comments');
    }
}
