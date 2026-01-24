<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CommentForm extends Form
{
    #[Validate('required|string|min:3|max:1000', onUpdate: false)]
    public string $content = '';

    #[Validate('boolean')]
    public bool $isSpoiler = false;

    public function resetFields(): void
    {
        $this->reset(['content', 'isSpoiler']);
    }
}
