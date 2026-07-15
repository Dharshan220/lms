<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function show(Request $request, Lesson $lesson)
    {
        $student = $request->user();
        $enrollmentId = $request->get('enrollment');

        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('user_id', $student->id)
            ->firstOrFail();

        abort_unless($lesson->is_published || $lesson->is_free, 403);

        $course = $lesson->course->load(['lessons' => function ($q) {
            $q->where('is_published', true)->orderBy('order_number');
        }]);

        $progress = LessonProgress::where('user_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->where('enrollment_id', $enrollment->id)
            ->first();

        $completedLessonIds = LessonProgress::where('user_id', $student->id)
            ->where('enrollment_id', $enrollment->id)
            ->where('is_completed', true)
            ->pluck('lesson_id');

        $previousLesson = null;
        $nextLesson = null;
        $foundCurrent = false;

        foreach ($course->lessons as $l) {
            if ($l->id === $lesson->id) {
                $foundCurrent = true;
                continue;
            }
            if (!$foundCurrent) {
                $previousLesson = $l;
            } else {
                $nextLesson = $l;
                break;
            }
        }

        $learningMaterials = $lesson->learningMaterials;

        return view('student.lessons.show', compact(
            'lesson',
            'enrollment',
            'course',
            'progress',
            'completedLessonIds',
            'previousLesson',
            'nextLesson',
            'learningMaterials'
        ));
    }

    public function complete(Request $request, Lesson $lesson)
    {
        $student = $request->user();
        $enrollmentId = $request->get('enrollment');

        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('user_id', $student->id)
            ->firstOrFail();

        $progress = LessonProgress::firstOrCreate([
            'user_id' => $student->id,
            'lesson_id' => $lesson->id,
            'enrollment_id' => $enrollment->id,
        ], [
            'is_completed' => false,
            'watch_time_seconds' => 0,
        ]);

        if (!$progress->is_completed) {
            $progress->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);

            $student->increment('xp_points', 10);

            $this->updateEnrollmentProgress($enrollment);
        }

        return redirect()->route('student.lessons.show', ['lesson' => $lesson, 'enrollment' => $enrollment])
            ->with('success', 'Lesson completed! +10 XP');
    }

    private function updateEnrollmentProgress(Enrollment $enrollment): void
    {
        $course = $enrollment->course;
        $totalLessons = $course->lessons()->where('is_published', true)->count();

        if ($totalLessons === 0) {
            return;
        }

        $completedLessons = LessonProgress::where('user_id', $enrollment->user_id)
            ->where('enrollment_id', $enrollment->id)
            ->where('is_completed', true)
            ->count();

        $progress = round(($completedLessons / $totalLessons) * 100, 2);

        $updateData = ['progress_percentage' => $progress];

        if ($progress >= 100) {
            $updateData['is_completed'] = true;
            $updateData['completed_at'] = now();
        }

        $enrollment->update($updateData);
    }
}
