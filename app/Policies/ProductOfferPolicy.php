<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ProductOffer;

class ProductOfferPolicy
{
    public function view(User $user, ProductOffer $offer): bool
    {
        return $offer->status === 'approved' && 
               $offer->expiry_date >= now()->toDateString() ||
               $user->isAdmin() ||
               $user->id === $offer->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isActive() && !$user->isSuspended();
    }

    public function update(User $user, ProductOffer $offer): bool
    {
        return $user->id === $offer->user_id && 
               $offer->status === 'pending' &&
               $user->isActive() && 
               !$user->isSuspended();
    }

    public function delete(User $user, ProductOffer $offer): bool
    {
        return ($user->id === $offer->user_id && $offer->status === 'pending') ||
               $user->isAdmin();
    }
}