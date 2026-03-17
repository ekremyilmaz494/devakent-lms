<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff: sadece kendisine atanmış eğitimleri görebilir
        return $course->enrollments()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Course $course): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->hasRole('admin');
    }
}
