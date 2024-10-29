<?php

namespace App\Policies;

use App\enum\UserRoleEnum;
use App\Models\MenuCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MenuCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->business == null){
            return false;
        }
        return $user->hasRole(UserRoleEnum::BUSINESS);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MenuCategory $menuCategory): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(UserRoleEnum::BUSINESS);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MenuCategory $menuCategory): bool
    {
        if(!$user->hasRole(UserRoleEnum::ADMIN) ||  $user->business == null){
            return false;
        }
        return $user->hasRole(UserRoleEnum::ADMIN) ||
        ($user->hasRole(UserRoleEnum::BUSINESS) && $user->business->id == $menuCategory->business_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MenuCategory $menuCategory): bool
    {
        if(!$user->hasRole(UserRoleEnum::ADMIN) ||  $user->business == null){
            return false;
        }
        return $user->hasRole(UserRoleEnum::ADMIN) ||
        ($user->hasRole(UserRoleEnum::BUSINESS) && $user->business->id == $menuCategory->business_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MenuCategory $menuCategory): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MenuCategory $menuCategory): bool
    {
        return true;
    }
}
