<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
        // 1. Create the Master Admin
        User::updateOrCreate(
            ['email' => 'admin@system.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'), // Default password
                'role' => 'Admin',
            ]
        );

        // 2. Create a default Receptionist
        User::updateOrCreate(
            ['email' => 'frontdesk@system.com'],
            [
                'name' => 'Sarah (Reception)',
                'password' => Hash::make('password123'),
                'role' => 'Receptionist',
            ]
        );

        // 3. Create a default Staff Member
        User::updateOrCreate(
            ['email' => 'staff@system.com'],
            [
                'name' => 'Dr. Smith',
                'password' => Hash::make('password123'),
                'role' => 'Staff',
            ]
        );

        // 4. Four New Staff Members
        $extraStaff = [
            ['name' => 'Dr. Joy', 'email' => 'joy@system.com'],
            ['name' => 'Dr. Brown', 'email' => 'brown@system.com'],
            ['name' => 'Dr. Sam', 'email' => 'sam@system.com'],
            ['name' => 'Dr. Lee', 'email' => 'lee@system.com'],
        ];

        foreach ($extraStaff as $staff) {
            User::updateOrCreate(
                ['email' => $staff['email']],
                [
                    'name' => $staff['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'Staff',
                ]
            );
        }

        $this->command->info('Default users seeded successfully!');
        $this->command->info('Admin Login: admin@system.com / password123');
    }
}
