<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user): bool
    {
        return true;
    }

    public function create(): void
    {
        Auth:check();
    }

    public function update(User $user, Task $task): void
    {
        Auth:check();
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->isCreator($task);
    }
}
