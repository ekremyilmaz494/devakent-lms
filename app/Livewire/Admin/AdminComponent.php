<?php

namespace App\Livewire\Admin;

use Livewire\Component;

abstract class AdminComponent extends Component
{
    public function boot(): void
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);
    }
}
