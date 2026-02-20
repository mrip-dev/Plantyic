<?php

namespace App\Services;

use App\Models\Goal;

class GoalService
{
    public function create(array $data): Goal
    {
        return Goal::create($data);
    }

    public function update(Goal $goal, array $data): Goal
    {
        $goal->update($data);
        return $goal;
    }

    public function delete(Goal $goal): void
    {
        $goal->delete();
    }
}
