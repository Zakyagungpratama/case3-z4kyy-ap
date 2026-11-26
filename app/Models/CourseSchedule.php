<?php

namespace App\Models;

use Database\Factories\CourseScheduleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $course_name
 * @property string $lecturer
 * @property string $day
 * @property string $starts_at
 * @property string $ends_at
 * @property string $room
 */
#[Fillable(['course_name', 'lecturer', 'day', 'starts_at', 'ends_at', 'room'])]
class CourseSchedule extends Model
{
    /** @use HasFactory<CourseScheduleFactory> */
    use HasFactory, SoftDeletes;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
