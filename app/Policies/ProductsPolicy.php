<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductsPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, Product $products): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Product $products): bool
    {
    }

    public function delete(User $user, Product $products): bool
    {
    }

    public function restore(User $user, Product $products): bool
    {
    }

    public function forceDelete(User $user, Product $products): bool
    {
    }
}
