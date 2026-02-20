<?php

namespace App\Enums\Workspace;

enum WorkspacePlan: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case ENTERPRISE = 'enterprise';
}
