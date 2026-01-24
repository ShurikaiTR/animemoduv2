<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ReviewForm extends Form
{
    #[Validate('required|string|min:10|max:2000', onUpdate: false)]
    public string $content = '';

    #[Validate('nullable|string|max:100')]
    public string $title = '';

    #[Validate('required|integer|min:1|max:10')]
    public int $rating = 0;

    #[Validate('boolean')]
    public bool $isSpoiler = false;

    public function resetFields(): void
    {
        $this->reset(['content', 'title', 'rating', 'isSpoiler']);
    }
}
