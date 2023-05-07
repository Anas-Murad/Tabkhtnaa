<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function index(User $user): bool
    {
        return true;
    }

    public function view(User $user, Meal $meal): bool
    {
        return $user->id === $meal->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Meal $meal): bool
    {
        return $user->id === $meal->user_id;
    }

    public function delete(User $user, Meal $meal): bool
    {
        return $user->id === $meal->user_id;
    }

    public function restore(User $user, Meal $meal): bool
    {
        return $user->id === $meal->user_id;
    }

    public function forceDelete(User $user, Meal $meal): bool
    {
        return $user->id === $meal->user_id;
    }
}
