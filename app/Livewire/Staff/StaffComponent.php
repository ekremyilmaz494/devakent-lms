<?php

namespace App\Livewire\Staff;

use Livewire\Component;

abstract class StaffComponent extends Component
{
    public function boot(): void
    {
        abort_unless(auth()->user()?->hasRole('staff'), 403);
    }
}
