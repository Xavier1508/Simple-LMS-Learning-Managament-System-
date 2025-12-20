<?php

namespace Database\Seeders;

use App\Models\CourseClass;
use App\Models\CourseSession;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    public function run(): void
    {
        $classes = CourseClass::all();

        foreach ($classes as $class) {
            if ($class->sessions()->count() == 0) {
                $startDate = Carbon::now()->startOfWeek()->addDays(1)->setHour(13)->setMinute(0);

                for ($i = 1; $i <= 13; $i++) {
                    CourseSession::create([
                        'course_class_id' => $class->id,
                        'title' => "Session $i: Topik Pembahasan Ke-$i",
                        'learning_outcome' => "Pada sesi ini mahasiswa diharapkan mampu memahami konsep dasar topik ke-$i dan mengimplementasikannya.",
                        'session_number' => $i,
                        'start_time' => $startDate->copy(),
                        'end_time' => $startDate->copy()->addMinutes(100),
                        'delivery_mode' => $i % 2 == 0 ? 'Online - Zoom' : 'Onsite - Class',
                        'zoom_link' => $i % 2 == 0 ? 'https://binus.zoom.us/j/123456789' : null,
                    ]);

                    $startDate->addWeek();
                }
            }
        }
    }
}
