<?php

namespace App\Enums\Projects;

enum ProjectStatus: string
{
    case Active = 'active';
    case OnHold = 'on-hold';
    case Completed = 'completed';
}
