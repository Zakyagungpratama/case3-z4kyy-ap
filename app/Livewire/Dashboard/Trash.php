<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Concerns\InteractsWithAuthenticatedStudent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Trash extends Component
{
    use InteractsWithAuthenticatedStudent;

    public string $status = '';

    public function restoreSchedule(int $scheduleId): void
    {
        $student = $this->student();
        $student->courseSchedules()->onlyTrashed()->findOrFail($scheduleId)->restore();

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Jadwal berhasil dipulihkan.';
    }

    public function restoreTask(int $taskId): void
    {
        $student = $this->student();
        $student->studyTasks()->onlyTrashed()->findOrFail($taskId)->restore();

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Tugas berhasil dipulihkan.';
    }

    public function restoreTransaction(int $transactionId): void
    {
        $student = $this->student();
        $student->financeTransactions()->onlyTrashed()->findOrFail($transactionId)->restore();

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Transaksi berhasil dipulihkan.';
    }

    public function deleteSchedulePermanently(int $scheduleId): void
    {
        $student = $this->student();
        $student->courseSchedules()->onlyTrashed()->findOrFail($scheduleId)->forceDelete();

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Jadwal berhasil dihapus permanen.';
    }

    public function deleteTaskPermanently(int $taskId): void
    {
        $student = $this->student();
        $student->studyTasks()->onlyTrashed()->findOrFail($taskId)->forceDelete();

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Tugas berhasil dihapus permanen.';
    }

    public function deleteTransactionPermanently(int $transactionId): void
    {
        $student = $this->student();
        $student->financeTransactions()->onlyTrashed()->findOrFail($transactionId)->forceDelete();

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Transaksi berhasil dihapus permanen.';
    }

    public function render(): View
    {
        $student = $this->student();

        return view('livewire.dashboard.trash', [
            'schedules' => $student->courseSchedules()->onlyTrashed()->latest('deleted_at')->get(),
            'tasks' => $student->studyTasks()->onlyTrashed()->latest('deleted_at')->get(),
            'transactions' => $student->financeTransactions()->onlyTrashed()->latest('deleted_at')->get(),
        ]);
    }
}
