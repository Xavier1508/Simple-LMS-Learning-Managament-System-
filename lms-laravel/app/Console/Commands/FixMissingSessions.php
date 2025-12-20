<?php

namespace App\Console\Commands;

use App\Models\CourseClass;
use App\Models\CourseSession;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixMissingSessions extends Command
{
    protected $signature = 'course:fix-sessions';

    protected $description = 'Generate 13 default sessions for classes that have none';

    public function handle()
    {
        $this->info('Checking for classes with missing sessions...');

        $classes = CourseClass::withCount('sessions')->having('sessions_count', 0)->get();

        if ($classes->isEmpty()) {
            $this->info('All classes already have sessions. Good job!');

            return;
        }

        $bar = $this->output->createProgressBar(count($classes));
        $bar->start();

        foreach ($classes as $class) {
            $startDate = Carbon::now()->next('Monday')->setTime(13, 0);

            for ($i = 1; $i <= 13; $i++) {
                $isOnsite = $i % 2 != 0;

                CourseSession::create([
                    'course_class_id' => $class->id,
                    'session_number' => $i,
                    'title' => "Session $i: Topic about ".Str::limit($class->course->title ?? 'Subject', 20),
                    'learning_outcome' => "Students will understand the fundamental concepts of topic $i.",
                    'start_time' => $startDate->copy(),
                    'end_time' => $startDate->copy()->addMinutes(100),
                    'delivery_mode' => $isOnsite ? 'Onsite - Class' : 'Online - GSLC',
                    'zoom_link' => $isOnsite ? null : 'https://zoom.us/j/dummy-link',
                ]);

                $startDate->addWeek();
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Successfully generated sessions for '.$classes->count().' classes.');
    }
}
