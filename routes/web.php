<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StemKitController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Teacher\LessonController as TeacherLessonController;
use App\Http\Controllers\Teacher\QuizController as TeacherQuizController;
use App\Http\Controllers\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Teacher\LiveClassController;
use App\Http\Controllers\Teacher\AiLessonPlannerController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboard;
use App\Http\Controllers\Ai\ChatController;
use App\Http\Controllers\Ai\QuizGeneratorController;
use App\Http\Controllers\Ai\AssignmentGeneratorController;
use App\Http\Controllers\Ai\ProjectIdeaController;
use App\Http\Controllers\Ai\CodeDebuggerController;
use App\Http\Controllers\Ai\LearningRecommendationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/courses', [HomeController::class, 'courses'])->name('courses.public');
Route::get('/search', [SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'darkmode'])->group(function () {

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return match ($role) {
            'super_admin', 'school_admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    // Profile
    Route::get('/profile', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Theme Toggle
    Route::post('/theme/toggle', [ThemeController::class, 'toggle'])->name('theme.toggle');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Discussions
    Route::get('/discussions', [DiscussionController::class, 'index'])->name('discussions.index');
    Route::post('/discussions', [DiscussionController::class, 'store'])->name('discussions.store');
    Route::get('/discussions/{discussion}', [DiscussionController::class, 'show'])->name('discussions.show');
    Route::post('/discussions/{discussion}/replies', [DiscussionController::class, 'addReply'])->name('discussions.reply');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware(['role:super_admin,school_admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::resource('schools', SchoolController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('students', StudentController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::resource('courses', AdminCourseController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('stem-kits', StemKitController::class);
        Route::resource('announcements', AnnouncementController::class);
        Route::resource('certificates', AdminCertificateController::class)->only(['index', 'show', 'destroy']);

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/enrollments', [ReportController::class, 'enrollmentReport'])->name('enrollments');
            Route::get('/courses', [ReportController::class, 'courseReport'])->name('courses');
            Route::get('/students', [ReportController::class, 'studentReport'])->name('students');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Teacher Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('teacher')->name('teacher.')->middleware(['role:teacher'])->group(function () {
        Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');

        Route::resource('courses', TeacherCourseController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        Route::post('/courses/{course}/lessons', [TeacherLessonController::class, 'store'])->name('lessons.store');
        Route::get('/courses/{course}/lessons/{lesson}/edit', [TeacherLessonController::class, 'edit'])->name('lessons.edit');
        Route::put('/courses/{course}/lessons/{lesson}', [TeacherLessonController::class, 'update'])->name('lessons.update');
        Route::delete('/courses/{course}/lessons/{lesson}', [TeacherLessonController::class, 'destroy'])->name('lessons.destroy');

        Route::resource('quizzes', TeacherQuizController::class);
        Route::resource('assignments', TeacherAssignmentController::class)->only(['index', 'create', 'store', 'show']);
        Route::get('/assignments/{assignment}/submissions', [TeacherAssignmentController::class, 'submissions'])->name('assignments.submissions');
        Route::post('/assignments/{assignment}/grade/{submission}', [TeacherAssignmentController::class, 'grade'])->name('assignments.grade');

        Route::resource('live-classes', LiveClassController::class)->only(['index', 'create', 'store', 'update']);
        Route::post('/live-classes/{liveClass}/cancel', [LiveClassController::class, 'cancel'])->name('live-classes.cancel');

        Route::get('/ai-lesson-planner', [AiLessonPlannerController::class, 'index'])->name('ai-lesson-planner.index');
        Route::post('/ai-lesson-planner/generate', [AiLessonPlannerController::class, 'generate'])->name('ai-lesson-planner.generate');
    });

    /*
    |--------------------------------------------------------------------------
    | Student Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('student')->name('student.')->middleware(['role:student'])->group(function () {
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

        Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
        Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
        Route::get('/my-courses', [StudentCourseController::class, 'myCourses'])->name('courses.my');
        Route::get('/courses/{course}/learn', [StudentCourseController::class, 'continueLesson'])->name('courses.learn');

        Route::get('/lessons/{lesson}', [StudentLessonController::class, 'show'])->name('lessons.show');
        Route::post('/lessons/{lesson}/complete', [StudentLessonController::class, 'complete'])->name('lessons.complete');

        Route::get('/quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('quizzes.show');
        Route::post('/quizzes/{quiz}/start', [StudentQuizController::class, 'start'])->name('quizzes.start');
        Route::post('/quizzes/{quiz}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');

        Route::get('/assignments', [StudentAssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('assignments.submit');

        Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{certificate}/download', [StudentCertificateController::class, 'download'])->name('certificates.download');

        Route::get('/quizzes', function () {
            $student = auth()->user();
            $enrolledCourseIds = \App\Models\Enrollment::where('user_id', $student->id)->pluck('course_id');
            $quizzes = \App\Models\Quiz::whereIn('course_id', $enrolledCourseIds)->with('course')->latest()->paginate(15);
            return view('student.quizzes.index', compact('quizzes'));
        })->name('quizzes.index');

        Route::get('/discussions', [DiscussionController::class, 'index'])->name('student.discussions.index');

        Route::get('/badges', function () {
            $student = auth()->user();
            $allBadges = \App\Models\Badge::all();
            $earnedBadgeIds = $student->badges()->pluck('badge_id');
            return view('student.badges', ['allBadgesList' => $allBadges, 'earnedBadgeIds' => $earnedBadgeIds, 'badges' => $allBadges]);
        })->name('badges');

        Route::get('/leaderboard', function () {
            $students = \App\Models\User::where('role', 'student')->where('is_active', true)
                ->orderByDesc('xp_points')->orderByDesc('level')->take(50)->get();
            $rank = 1;
            return view('student.leaderboard', compact('students', 'rank'));
        })->name('leaderboard');

        Route::get('/learning-paths', function () {
            $learningPaths = \App\Models\LearningPath::with(['courses.category', 'courses.teacher'])->latest()->paginate(12);
            return view('student.learning-paths.index', compact('learningPaths'));
        })->name('learning-paths');

        Route::get('/profile', [StudentProfileController::class, 'show'])->name('student.profile.show');
        Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('student.profile.edit');
        Route::patch('/profile', [StudentProfileController::class, 'update'])->name('student.profile.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Parent Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('parent')->name('parent.')->middleware(['role:parent'])->group(function () {
        Route::get('/dashboard', [ParentDashboard::class, 'index'])->name('dashboard');
        Route::get('/child/{user}', [ParentDashboard::class, 'childProgress'])->name('child.progress');
        Route::get('/reports', function () {
            $parent = auth()->user();
            $children = $parent->children()->with('school')->get();
            return view('parent.reports', compact('children'));
        })->name('reports');
    });

    /*
    |--------------------------------------------------------------------------
    | AI Features Routes (Student & Teacher)
    |--------------------------------------------------------------------------
    */
    Route::prefix('ai')->name('ai.')->middleware(['role:student,teacher'])->group(function () {
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat', [ChatController::class, 'chat'])->name('chat.send');
        Route::post('/chat/save', [ChatController::class, 'store'])->name('chat.save');

        Route::get('/quiz-generator', [QuizGeneratorController::class, 'index'])->name('quiz-generator.index');
        Route::post('/quiz-generator/generate', [QuizGeneratorController::class, 'generate'])->name('quiz-generator.generate');
        Route::post('/quiz-generator/save', [QuizGeneratorController::class, 'save'])->name('quiz-generator.save');

        Route::get('/assignment-generator', [AssignmentGeneratorController::class, 'index'])->name('assignment-generator.index');
        Route::post('/assignment-generator/generate', [AssignmentGeneratorController::class, 'generate'])->name('assignment-generator.generate');

        Route::get('/project-ideas', [ProjectIdeaController::class, 'index'])->name('project-ideas.index');
        Route::post('/project-ideas/generate', [ProjectIdeaController::class, 'generate'])->name('project-ideas.generate');

        Route::get('/code-debugger', [CodeDebuggerController::class, 'index'])->name('code-debugger.index');
        Route::post('/code-debugger/debug', [CodeDebuggerController::class, 'debug'])->name('code-debugger.debug');

        Route::get('/recommendations', [LearningRecommendationController::class, 'index'])->name('recommendations.index');
    });
});
