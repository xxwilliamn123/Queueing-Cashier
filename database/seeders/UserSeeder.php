<?php

namespace Database\Seeders;

use App\Models\TellerCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories if they don't exist
        $paymentCategory = TellerCategory::firstOrCreate(
            ['prefix' => 'P'],
            ['name' => 'Payment']
        );

        $disbursementCategory = TellerCategory::firstOrCreate(
            ['prefix' => 'D'],
            ['name' => 'Disbursement']
        );

        $othersCategory = TellerCategory::firstOrCreate(
            ['prefix' => 'R'],
            ['name' => 'Others']
        );

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@norsu.edu.ph'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'category_id' => null,
                'counter_name' => null,
            ]
        );

        // Create Teller Users for Payment Category
        User::firstOrCreate(
            ['email' => 'teller1@norsu.edu.ph'],
            [
                'name' => 'Maria Santos',
                'password' => Hash::make('password'),
                'role' => 'teller',
                'category_id' => $paymentCategory->id,
                'counter_name' => '1',
            ]
        );

        User::firstOrCreate(
            ['email' => 'teller2@norsu.edu.ph'],
            [
                'name' => 'Juan Dela Cruz',
                'password' => Hash::make('password'),
                'role' => 'teller',
                'category_id' => $paymentCategory->id,
                'counter_name' => '2',
            ]
        );

        // Create Teller Users for Disbursement Category
        User::firstOrCreate(
            ['email' => 'teller3@norsu.edu.ph'],
            [
                'name' => 'Ana Garcia',
                'password' => Hash::make('password'),
                'role' => 'teller',
                'category_id' => $disbursementCategory->id,
                'counter_name' => '3',
            ]
        );

        User::firstOrCreate(
            ['email' => 'teller4@norsu.edu.ph'],
            [
                'name' => 'Carlos Rodriguez',
                'password' => Hash::make('password'),
                'role' => 'teller',
                'category_id' => $disbursementCategory->id,
                'counter_name' => '4',
            ]
        );

        // Create Teller User for Others Category
        User::firstOrCreate(
            ['email' => 'teller5@norsu.edu.ph'],
            [
                'name' => 'Liza Martinez',
                'password' => Hash::make('password'),
                'role' => 'teller',
                'category_id' => $othersCategory->id,
                'counter_name' => '5',
            ]
        );

        // Create additional dummy tellers
        User::firstOrCreate(
            ['email' => 'teller6@norsu.edu.ph'],
            [
                'name' => 'Roberto Tan',
                'password' => Hash::make('password'),
                'role' => 'teller',
                'category_id' => $paymentCategory->id,
                'counter_name' => '6',
            ]
        );

        User::firstOrCreate(
            ['email' => 'teller7@norsu.edu.ph'],
            [
                'name' => 'Jennifer Lopez',
                'password' => Hash::make('password'),
                'role' => 'teller',
                'category_id' => $disbursementCategory->id,
                'counter_name' => '7',
            ]
        );

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@norsu.edu.ph / password');
        $this->command->info('Tellers: teller1@norsu.edu.ph to teller7@norsu.edu.ph / password');
    }
}
