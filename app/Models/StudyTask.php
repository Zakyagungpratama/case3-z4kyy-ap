<?php

namespace App\Models;

use Database\Factories\StudyTaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $course_name
 * @property Carbon $deadline
 * @property string $priority
 * @property bool $is_completed
 */
#[Fillable(['title', 'course_name', 'deadline', 'priority', 'is_completed'])]
class StudyTask extends Model
{
    /** @use HasFactory<StudyTaskFactory> */
    use HasFactory, SoftDeletes;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'is_completed' => 'boolean',
        ];
    }
}
