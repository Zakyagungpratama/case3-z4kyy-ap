<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Concerns\InteractsWithAuthenticatedStudent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Overview extends Component
{
    use InteractsWithAuthenticatedStudent;

    public function render(): View
    {
        $student = $this->student();
        $stats = Cache::remember('saku-kuliah:dashboard:stats:'.$student->id, now()->addMinutes(5), function () use ($student): array {
            return [
                'courses' => $student->courseSchedules()->count(),
                'open_tasks' => $student->studyTasks()->where('is_completed', false)->count(),
                'income' => (int) $student->financeTransactions()->where('type', 'income')->sum('amount'),
                'expenses' => (int) $student->financeTransactions()
                    ->where('type', 'expense')
                    ->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)
                    ->sum('amount'),
            ];
        });

        $weekdays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $todaySchedule = $student->courseSchedules()
            ->where('day', $weekdays[now()->dayOfWeek])
            ->orderBy('starts_at')
            ->get();
        $upcomingTasks = $student->studyTasks()
            ->where('is_completed', false)
            ->where('deadline', '>=', now())
            ->orderBy('deadline')
            ->limit(4)
            ->get();

        return view('livewire.dashboard.overview', compact('student', 'stats', 'todaySchedule', 'upcomingTasks'));
    }
}
