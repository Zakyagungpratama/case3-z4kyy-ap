<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Concerns\InteractsWithAuthenticatedStudent;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Profile extends Component
{
    use InteractsWithAuthenticatedStudent;

    public string $name = '';

    public string $nim = '';

    public string $faculty = '';

    public string $studyProgram = '';

    public string $status = '';

    public function mount(): void
    {
        $student = $this->student();

        $this->name = $student->name;
        $this->nim = $student->nim ?? '';
        $this->faculty = $student->faculty ?? '';
        $this->studyProgram = $student->study_program ?? '';
    }

    public function save(): void
    {
        $student = $this->student();
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:30', Rule::unique('users', 'nim')->ignore($student->id)],
            'faculty' => ['nullable', 'string', 'max:255'],
            'studyProgram' => ['nullable', 'string', 'max:255'],
        ]);

        $student->update([
            'name' => $validated['name'],
            'nim' => $validated['nim'] ?: null,
            'faculty' => $validated['faculty'] ?: null,
            'study_program' => $validated['studyProgram'] ?: null,
        ]);

        $this->status = 'Profil berhasil diperbarui.';
    }

    public function render(): View
    {
        return view('livewire.dashboard.profile', ['student' => $this->student()]);
    }
}
