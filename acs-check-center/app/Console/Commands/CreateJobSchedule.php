<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateJobSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobschedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create JobSchedule';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now()->format('Y-m-d');

        $existingSchedule = DB::table('job_schedules')
            ->where('job_schedule_date', $date)
            ->exists();

        if ($existingSchedule) {
            $this->error("Cannot run the task: Job schedules already exist for date {$date}.");
            return 1;
        }

        $authorities = DB::table('user_authorities')->where('user_authority_status', '=', '1')->whereNull('deleted_at')->get();
        $workshifts = DB::table('work_shifts')->whereNull('deleted_at')->where('work_shift_status', '1')->get();
        
        $data = [];
        foreach ($authorities as $author) {
            foreach ($workshifts as $work) {
                $data[] = [
                    'job_authority_id' => $author->user_authority_id,
                    'job_schedule_date' => $date,
                    'job_schedule_shift_id' => $work->work_shift_id,
                    'job_schedule_status_id' => 3,
                    'job_schedule_issue_id' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('job_schedules')->insert($data);
        $this->info("Job schedules created successfully for date {$date}.");
        return 0;
    }

}
