<?php

namespace App\Enums\Tasks;

enum TaskPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
}
