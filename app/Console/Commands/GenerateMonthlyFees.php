<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Fee;
use Carbon\Carbon;

class GenerateMonthlyFees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fees:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly hostel fees for all active students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating monthly fees...');

        $students = User::where('role', 'student')->get();
        $count = 0;
        $amount = 5000; // Fixed monthly fee, could be dynamic based on room type

        foreach ($students as $student) {
            // Check if fee already exists for this month
            $exists = Fee::where('student_id', $student->id)
                ->where('type', 'monthly')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->exists();

            if (!$exists) {
                Fee::create([
                    'student_id' => $student->id,
                    'amount' => $amount,
                    'due_date' => Carbon::now()->addDays(10), // Due in 10 days
                    'status' => 'pending',
                    'type' => 'monthly',
                ]);
                $count++;
            }
        }

        $this->info("Generated fees for {$count} students.");
    }
}
