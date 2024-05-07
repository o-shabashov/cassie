<?php

namespace App\Policies;

use App\Models\Products;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductsPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Products $products): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Products $products): bool
    {
    }

    public function delete(User $user, Products $products): bool
    {
    }

    public function restore(User $user, Products $products): bool
    {
    }

    public function forceDelete(User $user, Products $products): bool
    {
    }
}
