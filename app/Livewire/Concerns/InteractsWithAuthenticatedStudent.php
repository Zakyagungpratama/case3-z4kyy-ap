<?php

namespace App\Livewire\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait InteractsWithAuthenticatedStudent
{
    protected function student(): User
    {
        $student = Auth::user();

        abort_unless($student instanceof User, 403);

        return $student;
    }
}
