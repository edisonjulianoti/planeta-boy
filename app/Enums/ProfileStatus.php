<?php

declare(strict_types=1);

namespace App\Enums;

enum ProfileStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended';
    case PendingReview = 'pending_review';
}
