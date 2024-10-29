<?php

namespace App\Policies;

use App\enum\UserRoleEnum;
use App\Models\Business;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BusinessPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Business $business): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Business $business): bool
    {
        return $user->hasRole(UserRoleEnum::ADMIN) ||
            ($business->user_id == $user->id && $user->hasRole(UserRoleEnum::BUSINESS));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Business $business): bool
    {
        return $user->hasRole(UserRoleEnum::ADMIN) ||
        ($business->user_id == $user->id && $user->hasRole(UserRoleEnum::BUSINESS));
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Business $business): bool
    {
        return $user->hasRole(UserRoleEnum::ADMIN);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Business $business): bool
    {
        return $user->hasRole(UserRoleEnum::ADMIN) ;
    }

    /**
     * Kullanıcının favori işletmeleri ekleyip ekleyemeyeceğini kontrol et
     */
    public function addToFavorites(User $user): bool
    {
        // Kullanıcının rolüne göre kontrol yapabilirsiniz. Örneğin:
        return $user->hasRole(UserRoleEnum::USER);
    }

    /**
     * Kullanıcının favori işletmeleri çıkarıp çıkaramayacağını kontrol et
     */
    public function removeFromFavorites(User $user): bool
    {
        // Kullanıcının rolüne göre kontrol yapabilirsiniz.
        return $user->hasRole(UserRoleEnum::USER);
    }

    public function viewFavorites(User $user)
{
    // Kullanıcının favori işletmeleri görüntülemesine izin ver
    return $user->hasRole(UserRoleEnum::USER);
}
}
