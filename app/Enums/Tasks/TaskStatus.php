<?php

namespace App\Enums\Tasks;

enum TaskStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in-progress';
    case Done = 'done';
}
