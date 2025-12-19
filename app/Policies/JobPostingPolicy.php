<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobPosting;

class JobPostingPolicy
{
    public function view(User $user, JobPosting $job): bool
    {
        return $job->status === 'approved' && 
               $job->app_deadline >= now()->toDateString() ||
               $user->isAdmin() ||
               $user->id === $job->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isActive() && !$user->isSuspended();
    }

    public function update(User $user, JobPosting $job): bool
    {
        return $user->id === $job->user_id && 
               $job->status === 'pending' &&
               $user->isActive() && 
               !$user->isSuspended();
    }

    public function delete(User $user, JobPosting $job): bool
    {
        return ($user->id === $job->user_id && $job->status === 'pending') ||
               $user->isAdmin();
    }
}