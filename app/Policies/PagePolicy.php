<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Page $page): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Page $page): bool
    {
    }

    public function delete(User $user, Page $page): bool
    {
    }

    public function restore(User $user, Page $page): bool
    {
    }

    public function forceDelete(User $user, Page $page): bool
    {
    }
}
