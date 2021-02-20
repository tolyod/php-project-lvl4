<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\GuardHelpers;

class TaskPolicy
{
    use HandlesAuthorization;
    use GuardHelpers;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user): bool
    {
        return true;
    }

    public function create(): bool
    {
        return $this->check();
    }

    public function update(): bool
    {
        return $this->check();
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->isCreator($task);
    }
}
