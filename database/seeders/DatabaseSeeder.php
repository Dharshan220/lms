<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\School;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Badge;
use App\Models\StemKit;
use App\Models\Setting;
use App\Models\LearningPath;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Assignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() > 0) {
            $this->command->info('Database already seeded. Skipping...');
            return;
        }

        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@nanospark.com',
            'password' => 'password',
            'role' => 'super_admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Schools
        $schools = [
            ['name' => 'Delhi Public School', 'slug' => 'dps-delhi', 'city' => 'Delhi', 'state' => 'Delhi'],
            ['name' => 'St. Xavier Academy', 'slug' => 'st-xavier', 'city' => 'Mumbai', 'state' => 'Maharashtra'],
            ['name' => 'Greenwood International', 'slug' => 'greenwood', 'city' => 'Bangalore', 'state' => 'Karnataka'],
        ];

        foreach ($schools as $schoolData) {
            School::create(array_merge($schoolData, [
                'is_active' => true,
                'phone' => '9876543210',
                'email' => $schoolData['slug'] . '@school.com',
            ]));
        }

        // Create School Admins
        School::all()->each(function ($school, $index) {
            User::create([
                'name' => $school->name . ' Admin',
                'email' => 'schooladmin' . ($index + 1) . '@nanospark.com',
                'password' => 'password',
                'role' => 'school_admin',
                'school_id' => $school->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        });

        // Create Teachers
        $teacherNames = [
            ['name' => 'Dr. Priya Sharma', 'email' => 'priya@nanospark.com'],
            ['name' => 'Prof. Rahul Verma', 'email' => 'rahul@nanospark.com'],
            ['name' => 'Ms. Anita Patel', 'email' => 'anita@nanospark.com'],
        ];

        $teachers = [];
        foreach ($teacherNames as $i => $t) {
            $teachers[] = User::create([
                'name' => $t['name'],
                'email' => $t['email'],
                'password' => 'password',
                'role' => 'teacher',
                'school_id' => School::all()->random()->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create Students
        for ($i = 1; $i <= 15; $i++) {
            User::create([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@nanospark.com',
                'password' => 'password',
                'role' => 'student',
                'school_id' => School::all()->random()->id,
                'grade' => rand(6, 12),
                'xp_points' => rand(0, 5000),
                'daily_streak' => rand(0, 30),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create Parent
        User::create([
            'name' => 'Parent User',
            'email' => 'parent@nanospark.com',
            'password' => 'password',
            'role' => 'parent',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Categories
        $categories = [
            ['name' => 'IoT & Embedded', 'slug' => 'iot-embedded', 'icon' => 'bi-cpu', 'color' => '#FF6B35'],
            ['name' => 'Robotics', 'slug' => 'robotics', 'icon' => 'bi-robot', 'color' => '#4ECDC4'],
            ['name' => 'Artificial Intelligence', 'slug' => 'ai', 'icon' => 'bi-brain', 'color' => '#9B59B6'],
            ['name' => 'Programming', 'slug' => 'programming', 'icon' => 'bi-code-slash', 'color' => '#3498DB'],
            ['name' => 'Electronics', 'slug' => 'electronics', 'icon' => 'bi-lightning', 'color' => '#F39C12'],
            ['name' => 'STEM Basics', 'slug' => 'stem-basics', 'icon' => 'bi-book', 'color' => '#2ECC71'],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, [
                'description' => 'Learn about ' . $cat['name'],
                'is_active' => true,
            ]));
        }

        // Create Courses
        $courses = [
            ['title' => 'Introduction to Electronics', 'slug' => 'intro-electronics', 'level' => 'beginner', 'category' => 'electronics', 'duration_hours' => 10],
            ['title' => 'Arduino Basics', 'slug' => 'arduino-basics', 'level' => 'beginner', 'category' => 'iot-embedded', 'duration_hours' => 15],
            ['title' => 'ESP8266 & IoT', 'slug' => 'esp8266-iot', 'level' => 'intermediate', 'category' => 'iot-embedded', 'duration_hours' => 20],
            ['title' => 'Sensors & Actuators', 'slug' => 'sensors-actuators', 'level' => 'intermediate', 'category' => 'iot-embedded', 'duration_hours' => 18],
            ['title' => 'ESP32 Programming', 'slug' => 'esp32-programming', 'level' => 'advanced', 'category' => 'iot-embedded', 'duration_hours' => 25],
            ['title' => 'Robotics with Arduino', 'slug' => 'robotics-arduino', 'level' => 'intermediate', 'category' => 'robotics', 'duration_hours' => 22],
            ['title' => 'Smart Home Automation', 'slug' => 'smart-home', 'level' => 'advanced', 'category' => 'iot-embedded', 'duration_hours' => 30],
            ['title' => 'Python for Beginners', 'slug' => 'python-beginners', 'level' => 'beginner', 'category' => 'programming', 'duration_hours' => 12],
            ['title' => 'AI & Machine Learning Basics', 'slug' => 'ai-ml-basics', 'level' => 'intermediate', 'category' => 'ai', 'duration_hours' => 20],
            ['title' => 'Blynk & MQTT IoT', 'slug' => 'blynk-mqtt', 'level' => 'intermediate', 'category' => 'iot-embedded', 'duration_hours' => 16],
        ];

        $courseModels = [];
        foreach ($courses as $course) {
            $cat = Category::where('slug', $course['category'])->first();
            $courseModels[] = Course::create([
                'title' => $course['title'],
                'slug' => $course['slug'],
                'description' => 'Comprehensive course on ' . $course['title'] . '. Learn step by step with hands-on projects.',
                'short_description' => 'Master ' . $course['title'] . ' with practical projects.',
                'teacher_id' => $teachers[array_rand($teachers)]->id,
                'category_id' => $cat->id,
                'level' => $course['level'],
                'duration_hours' => $course['duration_hours'],
                'price' => 0,
                'is_featured' => in_array($course['slug'], ['arduino-basics', 'esp8266-iot', 'robotics-arduino']),
                'is_published' => true,
                'enrollment_count' => rand(10, 200),
                'rating' => rand(35, 50) / 10,
            ]);
        }

        // Create Lessons for each course
        foreach ($courseModels as $course) {
            $lessonCount = rand(5, 10);
            for ($i = 1; $i <= $lessonCount; $i++) {
                Lesson::create([
                    'course_id' => $course->id,
                    'title' => 'Lesson ' . $i . ': ' . $this->getRandomLessonTitle($course->category_id),
                    'slug' => $course->slug . '-lesson-' . $i,
                    'description' => 'In this lesson you will learn important concepts.',
                    'content_type' => collect(['video', 'text', 'pdf'])->random(),
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'order_number' => $i,
                    'duration_minutes' => rand(10, 45),
                    'is_free' => $i <= 2,
                    'is_published' => true,
                ]);
            }
        }

        // Create Badges
        $badges = [
            ['name' => 'First Steps', 'slug' => 'first-steps', 'description' => 'Enrolled in first course', 'icon' => 'bi-star-fill', 'color' => '#F1C40F', 'xp_reward' => 50, 'requirement_type' => 'enrollment', 'requirement_value' => 1],
            ['name' => 'Quick Learner', 'slug' => 'quick-learner', 'description' => 'Completed first lesson', 'icon' => 'bi-lightning-fill', 'color' => '#E67E22', 'xp_reward' => 100, 'requirement_type' => 'lessons_completed', 'requirement_value' => 1],
            ['name' => 'Knowledge Seeker', 'slug' => 'knowledge-seeker', 'description' => 'Completed 5 lessons', 'icon' => 'bi-book-fill', 'color' => '#3498DB', 'xp_reward' => 250, 'requirement_type' => 'lessons_completed', 'requirement_value' => 5],
            ['name' => 'Quiz Master', 'slug' => 'quiz-master', 'description' => 'Scored 100% on a quiz', 'icon' => 'bi-trophy-fill', 'color' => '#E74C3C', 'xp_reward' => 300, 'requirement_type' => 'quiz_perfect', 'requirement_value' => 1],
            ['name' => 'Course Champion', 'slug' => 'course-champion', 'description' => 'Completed first course', 'icon' => 'bi-award-fill', 'color' => '#9B59B6', 'xp_reward' => 500, 'requirement_type' => 'course_completed', 'requirement_value' => 1],
            ['name' => 'Streak Master', 'slug' => 'streak-master', 'description' => '7-day learning streak', 'icon' => 'bi-fire', 'color' => '#FF6B35', 'xp_reward' => 200, 'requirement_type' => 'streak', 'requirement_value' => 7],
            ['name' => 'IoT Explorer', 'slug' => 'iot-explorer', 'description' => 'Completed 3 IoT courses', 'icon' => 'bi-cpu', 'color' => '#1ABC9C', 'xp_reward' => 1000, 'requirement_type' => 'category_courses', 'requirement_value' => 3],
            ['name' => 'Robot Builder', 'slug' => 'robot-builder', 'description' => 'Completed robotics course', 'icon' => 'bi-gear-fill', 'color' => '#34495E', 'xp_reward' => 400, 'requirement_type' => 'course_completed', 'requirement_value' => 1],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }

        // Create STEM Kits
        $kits = [
            ['name' => 'Arduino Starter Kit', 'slug' => 'arduino-starter', 'category' => 'Arduino', 'difficulty_level' => 'beginner', 'price' => 2499, 'components' => ['Arduino Uno', 'USB Cable', 'Breadboard', 'Jumper Wires (M)', 'Jumper Wires (F)', 'LED Pack', 'Resistors Pack', 'Push Buttons (x5)', 'Potentiometer', 'Servo Motor']],
            ['name' => 'ESP8266 IoT Kit', 'slug' => 'esp8266-kit', 'category' => 'IoT', 'difficulty_level' => 'intermediate', 'price' => 3499, 'components' => ['NodeMCU ESP8266', 'Breadboard', 'DHT11 Sensor', 'Ultrasonic Sensor', 'Relay Module', 'OLED Display', 'LEDs', 'Resistors', 'Jumper Wires']],
            ['name' => 'Robotics Kit Pro', 'slug' => 'robotics-pro', 'category' => 'Robotics', 'difficulty_level' => 'advanced', 'price' => 5999, 'components' => ['Arduino Mega', 'Motor Driver (L298N)', 'DC Motors (x4)', 'Chassis', 'Caster Wheel', 'Ultrasonic Sensor', 'Servo Motor', 'IR Sensors (x2)', 'Battery Holder', 'Wheels (x4)']],
            ['name' => 'ESP32 Master Kit', 'slug' => 'esp32-master', 'category' => 'IoT', 'difficulty_level' => 'advanced', 'price' => 4999, 'components' => ['ESP32 DevKit', 'Breadboard', 'TFT Display', 'DHT22 Sensor', 'BME280 Sensor', 'Relay Module (4CH)', 'NeoPixel Ring', 'Servo Motor', 'Jumper Wires']],
            ['name' => 'Sensor Explorer Kit', 'slug' => 'sensor-explorer', 'category' => 'Sensors', 'difficulty_level' => 'beginner', 'price' => 1999, 'components' => ['Arduino Nano', 'Breadboard', 'Temperature Sensor', 'Light Sensor', 'Sound Sensor', 'Motion Sensor', 'IR Sensor', 'Buzzer', 'LEDs', 'Resistors']],
        ];

        foreach ($kits as $kit) {
            StemKit::create(array_merge($kit, [
                'description' => 'Complete ' . $kit['name'] . ' for hands-on learning.',
                'is_available' => true,
                'stock_quantity' => rand(10, 50),
            ]));
        }

        // Create Learning Paths
        $paths = [
            ['title' => 'IoT Beginner Path', 'description' => 'Start your IoT journey from scratch', 'level' => 'beginner', 'icon' => 'bi-lightbulb', 'color' => '#FF6B35'],
            ['title' => 'IoT Intermediate Path', 'description' => 'Build complex IoT projects', 'level' => 'intermediate', 'icon' => 'bi-router', 'color' => '#4ECDC4'],
            ['title' => 'Robotics Path', 'description' => 'Master robotics from basics to advanced', 'level' => 'intermediate', 'icon' => 'bi-robot', 'color' => '#9B59B6'],
            ['title' => 'AI & IoT Advanced Path', 'description' => 'Combine AI with IoT for smart solutions', 'level' => 'advanced', 'icon' => 'bi-brain', 'color' => '#E74C3C'],
        ];

        foreach ($paths as $path) {
            LearningPath::create(array_merge($path, ['is_active' => true]));
        }

        // Create Quizzes for Priya's courses (4, 10)
        $quizData = [
            ['course_id' => 4, 'title' => 'Sensors Fundamentals Quiz', 'time_limit' => 20, 'passing' => 6, 'max_attempts' => 3],
            ['course_id' => 4, 'title' => 'Actuators & Motors Quiz', 'time_limit' => 15, 'passing' => 5, 'max_attempts' => 3],
            ['course_id' => 10, 'title' => 'IoT Protocols Quiz', 'time_limit' => 25, 'passing' => 7, 'max_attempts' => 2],
        ];

        foreach ($quizData as $qd) {
            $quiz = Quiz::create([
                'course_id' => $qd['course_id'],
                'title' => $qd['title'],
                'description' => 'Test your knowledge on the covered topics.',
                'time_limit_minutes' => $qd['time_limit'],
                'passing_marks' => $qd['passing'],
                'max_attempts' => $qd['max_attempts'],
                'is_published' => true,
            ]);

            $questions = [
                ['question' => 'What does a sensor do?', 'option_a' => 'Produces output', 'option_b' => 'Measures physical quantity', 'option_c' => 'Stores data', 'option_d' => 'Transmits wirelessly', 'correct' => 'B', 'marks' => 2],
                ['question' => 'Which sensor measures distance?', 'option_a' => 'DHT11', 'option_b' => 'PIR', 'option_c' => 'HC-SR04', 'option_d' => 'LDR', 'correct' => 'C', 'marks' => 2],
                ['question' => 'What is the output of a temperature sensor?', 'option_a' => 'Voltage', 'option_b' => 'Temperature reading', 'option_c' => 'Light intensity', 'option_d' => 'Sound', 'correct' => 'B', 'marks' => 2],
                ['question' => 'Which pin type is most common for sensors?', 'option_a' => 'Digital', 'option_b' => 'Analog', 'option_c' => 'Both', 'option_d' => 'Serial', 'correct' => 'C', 'marks' => 2],
                ['question' => 'What does ADC stand for?', 'option_a' => 'Analog to Digital Converter', 'option_b' => 'Advanced Data Communication', 'option_c' => 'Auto Digital Control', 'option_d' => 'Analog Data Channel', 'correct' => 'A', 'marks' => 2],
                ['question' => 'Which is NOT a sensor type?', 'option_a' => 'Temperature', 'option_b' => 'Humidity', 'option_c' => 'Motor', 'option_d' => 'Proximity', 'correct' => 'C', 'marks' => 2],
                ['question' => 'What voltage does Arduino Uno operate at?', 'option_a' => '3.3V', 'option_b' => '5V', 'option_c' => '12V', 'option_d' => '9V', 'correct' => 'B', 'marks' => 1],
                ['question' => 'Which bus is used for I2C communication?', 'option_a' => 'SDA and SCL', 'option_b' => 'TX and RX', 'option_c' => 'MOSI and MISO', 'option_d' => 'D0 and D1', 'correct' => 'A', 'marks' => 2],
                ['question' => 'What is PWM used for?', 'option_a' => 'Serial communication', 'option_b' => 'Analog output simulation', 'option_c' => 'Digital input reading', 'option_d' => 'Memory management', 'correct' => 'B', 'marks' => 2],
                ['question' => 'What does a PIR sensor detect?', 'option_a' => 'Temperature', 'option_b' => 'Light', 'option_c' => 'Motion', 'option_d' => 'Sound', 'correct' => 'C', 'marks' => 2],
            ];

            foreach ($questions as $i => $q) {
                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'question' => $q['question'],
                    'question_type' => 'mcq',
                    'option_a' => $q['option_a'],
                    'option_b' => $q['option_b'],
                    'option_c' => $q['option_c'],
                    'option_d' => $q['option_d'],
                    'correct_answer' => $q['correct'],
                    'marks' => $q['marks'],
                    'order_number' => $i + 1,
                ]);
            }
        }

        // Create Assignments for Priya's courses
        $assignmentData = [
            ['course_id' => 4, 'title' => 'Build a Temperature Monitor', 'desc' => 'Create a circuit using DHT11 sensor to measure temperature and display it on Serial Monitor. Submit your code and circuit diagram.', 'due' => now()->addDays(14), 'marks' => 50],
            ['course_id' => 4, 'title' => 'Motion Detection System', 'desc' => 'Build a PIR sensor based motion detection system with LED indicator. Document your approach and submit the code.', 'due' => now()->addDays(21), 'marks' => 50],
            ['course_id' => 10, 'title' => 'MQTT Weather Station', 'desc' => 'Build an IoT weather station using ESP8266 that publishes temperature and humidity data via MQTT to a broker. Submit code and broker logs.', 'due' => now()->addDays(10), 'marks' => 100],
        ];

        foreach ($assignmentData as $ad) {
            Assignment::create([
                'course_id' => $ad['course_id'],
                'teacher_id' => 5,
                'title' => $ad['title'],
                'description' => $ad['desc'],
                'due_date' => $ad['due'],
                'max_marks' => $ad['marks'],
                'is_published' => true,
            ]);
        }

        // Create Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'Nano Spark LMS', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Learn IoT, Robotics & AI', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => '', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'hello@nanospark.com', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '+91 9876543210', 'group' => 'general'],
            ['key' => 'primary_color', 'value' => '#FF6B35', 'group' => 'appearance'],
            ['key' => 'secondary_color', 'value' => '#4ECDC4', 'group' => 'appearance'],
            ['key' => 'xp_per_lesson', 'value' => '10', 'group' => 'gamification'],
            ['key' => 'xp_per_quiz', 'value' => '25', 'group' => 'gamification'],
            ['key' => 'xp_per_assignment', 'value' => '50', 'group' => 'gamification'],
            ['key' => 'streak_bonus_xp', 'value' => '20', 'group' => 'gamification'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }

    private function getRandomLessonTitle(int $categoryId): string
    {
        $fallback = ['Introduction', 'Core Concepts', 'Hands-on Practice', 'Advanced Topics', 'Project Building', 'Testing & Debug', 'Best Practices', 'Real World Applications', 'Review', 'Assessment'];
        $titles = [
            1 => ['Getting Started', 'First Circuit', 'Understanding Voltage', 'Ohm\'s Law', 'Building a LED Circuit', 'Resistors 101', 'Capacitors Basics', 'Digital vs Analog', 'Introduction to PCB', 'Safety Guidelines'],
            2 => ['Basic Components', 'Breadboard Basics', 'LED Projects', 'Button Inputs', 'PWM Control', 'Servo Motors', 'Sensor Integration', 'Communication Basics', 'Wireless Setup', 'Final Project'],
        ];
        return collect($titles[$categoryId] ?? $fallback)->random();
    }
}
