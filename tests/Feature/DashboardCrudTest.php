<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\Finances;
use App\Livewire\Dashboard\Schedules;
use App\Livewire\Dashboard\Tasks;
use App\Livewire\Dashboard\Trash;
use App\Models\CourseSchedule;
use App\Models\FinanceTransaction;
use App\Models\StudyTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_create_update_and_soft_delete_a_schedule(): void
    {
        $student = User::factory()->create();
        $this->actingAs($student);

        Livewire::test(Schedules::class)
            ->set('courseName', 'Pemrograman Web')
            ->set('lecturer', 'Dosen Pertama')
            ->set('day', 'Senin')
            ->set('startsAt', '08:00')
            ->set('endsAt', '09:40')
            ->set('room', 'A101')
            ->call('save')
            ->assertHasNoErrors();

        $schedule = CourseSchedule::query()->firstOrFail();
        $this->assertDatabaseHas('course_schedules', [
            'id' => $schedule->id,
            'user_id' => $student->id,
            'course_name' => 'Pemrograman Web',
        ]);

        Livewire::test(Schedules::class)
            ->call('edit', $schedule->id)
            ->set('courseName', 'Pemrograman Lanjut')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('course_schedules', [
            'id' => $schedule->id,
            'course_name' => 'Pemrograman Lanjut',
        ]);

        Livewire::test(Schedules::class)
            ->call('delete', $schedule->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted($schedule);
    }

    public function test_student_cannot_edit_or_delete_another_students_schedule(): void
    {
        $student = User::factory()->create();
        $anotherStudentSchedule = CourseSchedule::factory()->create([
            'course_name' => 'Jadwal milik mahasiswa lain',
        ]);
        $this->actingAs($student);

        Livewire::test(Schedules::class)
            ->call('edit', $anotherStudentSchedule->id)
            ->assertNotFound();

        Livewire::test(Schedules::class)
            ->call('delete', $anotherStudentSchedule->id)
            ->assertNotFound();

        $this->assertModelExists($anotherStudentSchedule);
    }

    public function test_student_can_create_update_and_soft_delete_a_task(): void
    {
        $student = User::factory()->create();
        $this->actingAs($student);

        Livewire::test(Tasks::class)
            ->set('title', 'Rancang antarmuka')
            ->set('courseName', 'Pemrograman Web')
            ->set('deadline', now()->addDay()->format('Y-m-d\TH:i'))
            ->set('priority', 'Tinggi')
            ->call('save')
            ->assertHasNoErrors();

        $task = StudyTask::query()->firstOrFail();
        $this->assertDatabaseHas('study_tasks', [
            'id' => $task->id,
            'user_id' => $student->id,
            'title' => 'Rancang antarmuka',
        ]);

        Livewire::test(Tasks::class)
            ->call('edit', $task->id)
            ->set('title', 'Implementasi antarmuka')
            ->call('save')
            ->assertHasNoErrors();

        Livewire::test(Tasks::class)
            ->call('toggleCompletion', $task->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('study_tasks', [
            'id' => $task->id,
            'title' => 'Implementasi antarmuka',
            'is_completed' => true,
        ]);

        Livewire::test(Tasks::class)
            ->call('delete', $task->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted($task);
    }

    public function test_student_can_create_update_and_soft_delete_a_financial_transaction(): void
    {
        $student = User::factory()->create();
        $this->actingAs($student);

        Livewire::test(Finances::class)
            ->set('type', 'income')
            ->set('category', 'Uang Saku')
            ->set('description', 'Uang saku bulanan')
            ->set('amount', '1500000')
            ->set('date', now()->toDateString())
            ->call('save')
            ->assertHasNoErrors();

        $transaction = FinanceTransaction::query()->firstOrFail();
        $this->assertDatabaseHas('finance_transactions', [
            'id' => $transaction->id,
            'user_id' => $student->id,
            'amount' => 1500000,
        ]);

        Livewire::test(Finances::class)
            ->call('edit', $transaction->id)
            ->set('amount', '1750000')
            ->call('save')
            ->assertHasNoErrors();

        Livewire::test(Finances::class)
            ->call('delete', $transaction->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('finance_transactions', [
            'id' => $transaction->id,
            'amount' => 1750000,
        ]);
        $this->assertSoftDeleted($transaction);
    }

    public function test_student_can_only_view_and_manage_their_own_financial_transactions(): void
    {
        $student = User::factory()->create();
        $anotherStudent = User::factory()->create();
        $ownTransaction = FinanceTransaction::factory()->for($student)->create([
            'description' => 'Transaksi milik sendiri',
        ]);
        $anotherStudentsTransaction = FinanceTransaction::factory()->for($anotherStudent)->create([
            'description' => 'Transaksi rahasia mahasiswa lain',
        ]);
        $this->actingAs($student);

        Livewire::test(Finances::class)
            ->assertSee('Transaksi milik sendiri')
            ->assertDontSee('Transaksi rahasia mahasiswa lain')
            ->call('edit', $anotherStudentsTransaction->id)
            ->assertNotFound()
            ->call('delete', $anotherStudentsTransaction->id)
            ->assertNotFound();

        $this->assertModelExists($ownTransaction);
        $this->assertModelExists($anotherStudentsTransaction);
    }

    public function test_student_can_view_and_restore_owned_trashed_records(): void
    {
        $student = User::factory()->create();
        $schedule = CourseSchedule::factory()->for($student)->create(['course_name' => 'Jadwal dipulihkan']);
        $task = StudyTask::factory()->for($student)->create(['title' => 'Tugas dipulihkan']);
        $transaction = FinanceTransaction::factory()->for($student)->create(['description' => 'Transaksi dipulihkan']);
        $schedule->delete();
        $task->delete();
        $transaction->delete();
        $this->actingAs($student);
        $cacheKey = 'saku-kuliah:dashboard:stats:'.$student->id;
        Cache::put($cacheKey, ['courses' => 0], now()->addMinutes(5));

        Livewire::test(Trash::class)
            ->assertSee('Jadwal dipulihkan')
            ->assertSee('Tugas dipulihkan')
            ->assertSee('Transaksi dipulihkan')
            ->call('restoreSchedule', $schedule->id)
            ->call('restoreTask', $task->id)
            ->call('restoreTransaction', $transaction->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('course_schedules', ['id' => $schedule->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('study_tasks', ['id' => $task->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('finance_transactions', ['id' => $transaction->id, 'deleted_at' => null]);
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_student_cannot_restore_another_students_trashed_records(): void
    {
        $anotherStudent = User::factory()->create();
        $schedule = CourseSchedule::factory()->for($anotherStudent)->create();
        $task = StudyTask::factory()->for($anotherStudent)->create();
        $transaction = FinanceTransaction::factory()->for($anotherStudent)->create();
        $schedule->delete();
        $task->delete();
        $transaction->delete();
        $this->actingAs(User::factory()->create());

        Livewire::test(Trash::class)
            ->call('restoreSchedule', $schedule->id)
            ->assertNotFound()
            ->call('restoreTask', $task->id)
            ->assertNotFound()
            ->call('restoreTransaction', $transaction->id)
            ->assertNotFound();

        $this->assertSoftDeleted($schedule);
        $this->assertSoftDeleted($task);
        $this->assertSoftDeleted($transaction);
    }

    public function test_student_can_permanently_delete_owned_trashed_records(): void
    {
        $student = User::factory()->create();
        $schedule = CourseSchedule::factory()->for($student)->create();
        $task = StudyTask::factory()->for($student)->create();
        $transaction = FinanceTransaction::factory()->for($student)->create();
        $schedule->delete();
        $task->delete();
        $transaction->delete();
        $this->actingAs($student);
        $cacheKey = 'saku-kuliah:dashboard:stats:'.$student->id;
        Cache::put($cacheKey, ['courses' => 0], now()->addMinutes(5));

        Livewire::test(Trash::class)
            ->call('deleteSchedulePermanently', $schedule->id)
            ->call('deleteTaskPermanently', $task->id)
            ->call('deleteTransactionPermanently', $transaction->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('course_schedules', ['id' => $schedule->id]);
        $this->assertDatabaseMissing('study_tasks', ['id' => $task->id]);
        $this->assertDatabaseMissing('finance_transactions', ['id' => $transaction->id]);
        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_student_cannot_permanently_delete_another_students_trashed_records(): void
    {
        $anotherStudent = User::factory()->create();
        $schedule = CourseSchedule::factory()->for($anotherStudent)->create();
        $task = StudyTask::factory()->for($anotherStudent)->create();
        $transaction = FinanceTransaction::factory()->for($anotherStudent)->create();
        $schedule->delete();
        $task->delete();
        $transaction->delete();
        $this->actingAs(User::factory()->create());

        Livewire::test(Trash::class)
            ->call('deleteSchedulePermanently', $schedule->id)
            ->assertNotFound()
            ->call('deleteTaskPermanently', $task->id)
            ->assertNotFound()
            ->call('deleteTransactionPermanently', $transaction->id)
            ->assertNotFound();

        $this->assertSoftDeleted($schedule);
        $this->assertSoftDeleted($task);
        $this->assertSoftDeleted($transaction);
    }

    public function test_student_cannot_permanently_delete_active_records(): void
    {
        $student = User::factory()->create();
        $schedule = CourseSchedule::factory()->for($student)->create();
        $task = StudyTask::factory()->for($student)->create();
        $transaction = FinanceTransaction::factory()->for($student)->create();
        $this->actingAs($student);

        Livewire::test(Trash::class)
            ->call('deleteSchedulePermanently', $schedule->id)
            ->assertNotFound()
            ->call('deleteTaskPermanently', $task->id)
            ->assertNotFound()
            ->call('deleteTransactionPermanently', $transaction->id)
            ->assertNotFound();

        $this->assertModelExists($schedule);
        $this->assertModelExists($task);
        $this->assertModelExists($transaction);
    }

    public function test_trash_page_requires_authentication(): void
    {
        $this->get(route('trash'))
            ->assertRedirect(route('login'));
    }

    public function test_invalid_schedule_times_are_rejected(): void
    {
        $student = User::factory()->create();
        $this->actingAs($student);

        Livewire::test(Schedules::class)
            ->set('courseName', 'Pemrograman Web')
            ->set('lecturer', 'Dosen Pertama')
            ->set('day', 'Senin')
            ->set('startsAt', '10:00')
            ->set('endsAt', '09:00')
            ->set('room', 'A101')
            ->call('save')
            ->assertHasErrors(['endsAt']);

        $this->assertDatabaseCount('course_schedules', 0);
    }

    public function test_dashboard_cache_is_invalidated_after_creating_a_schedule(): void
    {
        $student = User::factory()->create();
        $cacheKey = 'saku-kuliah:dashboard:stats:'.$student->id;
        Cache::put($cacheKey, ['courses' => 0], now()->addMinutes(5));
        $this->actingAs($student);

        Livewire::test(Schedules::class)
            ->set('courseName', 'Pemrograman Web')
            ->set('lecturer', 'Dosen Pertama')
            ->set('day', 'Senin')
            ->set('startsAt', '08:00')
            ->set('endsAt', '09:40')
            ->set('room', 'A101')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertFalse(Cache::has($cacheKey));
    }
}
