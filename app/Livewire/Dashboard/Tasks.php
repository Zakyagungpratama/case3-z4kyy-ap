<?php

namespace App\Livewire\Dashboard;

use App\Livewire\Concerns\InteractsWithAuthenticatedStudent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Tasks extends Component
{
    use InteractsWithAuthenticatedStudent;

    public ?int $taskId = null;

    public string $title = '';

    public string $courseName = '';

    public string $deadline = '';

    public string $priority = 'Sedang';

    public string $filter = 'all';

    public string $status = '';

    private const PRIORITIES = ['Tinggi', 'Sedang', 'Rendah'];

    public function mount(): void
    {
        $this->deadline = now()->addDay()->setTime(23, 59)->format('Y-m-d\TH:i');
    }

    public function save(): void
    {
        $student = $this->student();
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'courseName' => ['required', 'string', 'max:255'],
            'deadline' => ['required', 'date'],
            'priority' => ['required', 'in:'.implode(',', self::PRIORITIES)],
        ]);
        $taskData = [
            'title' => $validated['title'],
            'course_name' => $validated['courseName'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
        ];

        if ($this->taskId) {
            $student->studyTasks()->findOrFail($this->taskId)->update($taskData);
        } else {
            $student->studyTasks()->create($taskData);
        }

        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->resetForm();
        $this->status = 'Tugas berhasil disimpan.';
    }

    public function edit(int $taskId): void
    {
        $task = $this->student()->studyTasks()->findOrFail($taskId);

        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->courseName = $task->course_name;
        $this->deadline = $task->deadline->format('Y-m-d\TH:i');
        $this->priority = $task->priority;
        $this->status = '';
    }

    public function toggleCompletion(int $taskId): void
    {
        $student = $this->student();
        $task = $student->studyTasks()->findOrFail($taskId);
        $task->update(['is_completed' => ! $task->is_completed]);
        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
    }

    public function delete(int $taskId): void
    {
        $student = $this->student();
        $student->studyTasks()->findOrFail($taskId)->delete();
        Cache::forget('saku-kuliah:dashboard:stats:'.$student->id);
        $this->status = 'Tugas berhasil dihapus.';
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->status = '';
    }

    public function render(): View
    {
        $student = $this->student();
        $tasks = $student->studyTasks();

        if ($this->filter === 'open') {
            $tasks->where('is_completed', false);
        } elseif ($this->filter === 'completed') {
            $tasks->where('is_completed', true);
        } elseif ($this->filter === 'high') {
            $tasks->where('priority', 'Tinggi');
        }

        return view('livewire.dashboard.tasks', [
            'tasks' => $tasks->orderBy('deadline')->get(),
            'priorities' => self::PRIORITIES,
            'totalTasks' => $student->studyTasks()->count(),
            'completedTasks' => $student->studyTasks()->where('is_completed', true)->count(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset('taskId', 'title', 'courseName');
        $this->deadline = now()->addDay()->setTime(23, 59)->format('Y-m-d\TH:i');
        $this->priority = 'Sedang';
        $this->resetValidation();
    }
}
