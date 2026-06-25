<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (!User::where('email', 'abc@gmail.com')->exists()) {
            User::create([
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
    }
}
