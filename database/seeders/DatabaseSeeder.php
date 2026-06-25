<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::where('email', 'abc@gmail.com')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => 'abc@gmail.com',
                'password' => bcrypt('123456789'),
                'superuser' => true,
                'isadmin' => true,
                'status' => true,
                'staff_id' => 'SUPER-001',
                'leave_balance' => 30,
            ]);
        }

        $dept = Department::firstOrCreate(
            ['name' => 'General'],
            ['admin_id' => $user->id, 'department_head_id' => null],
        );

        if (!$user->department_id) {
            $user->update(['department_id' => $dept->id]);
        }
    }
}
