<?php

declare(strict_types=1);

namespace App\Enums;

enum CommentTab: string
{
    case COMMENTS = 'comments';
    case REVIEWS = 'reviews';
}
