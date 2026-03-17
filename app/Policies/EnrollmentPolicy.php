<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\User;

class EnrollmentPolicy
{
    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $enrollment->user_id === $user->id;
    }

    public function update(User $user, Enrollment $enrollment): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->hasRole('admin');
    }
}
