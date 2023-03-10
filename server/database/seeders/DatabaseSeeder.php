<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Attendance;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\Job;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'name' => 'IT Department'
        ]);

        Department::create([
            'name' => 'Marketing Department'
        ]);

        Job::create([
            'name' => 'Software Engineer',
            'salary' => 1000,
        ]);

        Job::create([
            'name' => 'Junior Developer',
            'salary' => 500
        ]);

        Deduction::create([
            'name' => 'SSS',
            'amount' => 800
        ]);

        Deduction::create([
            'name' => 'Phil Health',
            'amount' => 300
        ]);

        Deduction::create([
            'name' => 'Pag Ibig',
            'amount' => 100
        ]);

        // Admin
        User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'first_name' => 'Admin',
            'last_name' => 'Account',
            'birthday' => new DateTime(),
            'gender' => 'male',
            'address' => 'test address',
            'phone' => 'phone',
            'is_admin' => 1,
            'department_id' => 1,
            'job_id' => 1,
        ]);

        // Test
        User::create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('password'),
            'first_name' => 'Test',
            'last_name' => 'Account',
            'birthday' => new DateTime(),
            'gender' => 'male',
            'address' => 'test address',
            'phone' => 'phone',
            'department_id' => 1,
            'job_id' => 1,
        ]);

        // Get test user
        $testUser = User::find(2);

        // Create 30 days of attendance
        for ($day = 30; $day > 0; $day--) {
            $testUser->attendances()->create([
                'clock_in' => Carbon::now()->subDay($day)->startOfDay()->addHour(8),
                'clock_out' => Carbon::now()->subDay($day)->startOfDay()->addHour(mt_rand(16, 20)),
            ]);
        }

        // Create 10 fake users
        User::factory(10)->create();

        // User 3 - 9 (Present)
        for ($i = 3; $i <= 9; $i++) {
            User::find($i)->attendances()->create([
                'clock_in' => Carbon::now()->startOfDay()->addHour(8),
                'clock_out' => Carbon::now()->startOfDay()->addHour(mt_rand(16, 20)),
            ]);
        }

        // User 10 - (Late)
        User::find(10)->attendances()->create([
            'clock_in' => Carbon::now()->startOfDay()->addHour(10),
            'clock_out' => Carbon::now()->startOfDay()->addHour(mt_rand(16, 20)),
        ]);

        // User 11 - on Leave
        User::find(11)->leaves()->create([
            'date' => Carbon::now(),
            'reason' => 'Sick leave',
            'status' => 'approved'
        ]);

        // User 12 - on Leave
        User::find(12)->leaves()->create([
            'date' => Carbon::now(),
            'reason' => 'Emergency leave',
            'status' => 'approved'
        ]);
    }
}
