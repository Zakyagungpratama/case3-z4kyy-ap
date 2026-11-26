<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Concerns\InteractsWithAuthenticatedStudent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Schedules extends Component
{
    use InteractsWithAuthenticatedStudent;

    public ?int $scheduleId = null;

    public string $courseName = '';

    public string $lecturer = '';

    public string $day = 'Senin';

    public string $startsAt = '08:00';

    public string $endsAt = '10:00';

    public string $room = '';

    public string $status = '';

    private const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    public function save(): void
    {
        $student = $this->student();
        $validated = $this->validate([
            'courseName' => ['required', 'string', 'max:255'],
            'lecturer' => ['required', 'string', 'max:255'],
            'day' => ['required', 'string', 'in:'.implode(',', self::DAYS)],
            'startsAt' => ['required', 'date_format:H:i'],
            'endsAt' => ['required', 'date_format:H:i', 'after:startsAt'],
            'room' => ['required', 'string', 'max:100'],
        ]);
        $scheduleData = [
            'course_name' => $validated['courseName'],
            'lecturer' => $validated['lecturer'],
            'day' => $validated['day'],
            'starts_at' => $validated['startsAt'],
            'ends_at' => $validated['endsAt'],
            'room' => $validated['room'],
        ];

        if ($this->scheduleId) {
            $student->courseSchedules()->findOrFail($this->scheduleId)->update($scheduleData);
        } else {
            $student->courseSchedules()->create($scheduleData);
        }

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->resetForm();
        $this->status = 'Jadwal berhasil disimpan.';
    }

    public function edit(int $scheduleId): void
    {
        $schedule = $this->student()->courseSchedules()->findOrFail($scheduleId);

        $this->scheduleId = $schedule->id;
        $this->courseName = $schedule->course_name;
        $this->lecturer = $schedule->lecturer;
        $this->day = $schedule->day;
        $this->startsAt = substr($schedule->starts_at, 0, 5);
        $this->endsAt = substr($schedule->ends_at, 0, 5);
        $this->room = $schedule->room;
        $this->status = '';
    }

    public function delete(int $scheduleId): void
    {
        $student = $this->student();
        $student->courseSchedules()->findOrFail($scheduleId)->delete();
        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Jadwal berhasil dihapus.';
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->status = '';
    }

    public function render(): View
    {
        $student = $this->student();

        return view('livewire.dashboard.schedules', [
            'schedules' => $student->courseSchedules()->orderByRaw("CASE day WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
                ->orderBy('starts_at')
                ->get(),
            'days' => self::DAYS,
        ]);
    }

    private function resetForm(): void
    {
        $this->reset('scheduleId', 'courseName', 'lecturer', 'room');
        $this->day = 'Senin';
        $this->startsAt = '08:00';
        $this->endsAt = '10:00';
        $this->resetValidation();
    }
}
