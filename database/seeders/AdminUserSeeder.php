<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $superAdmin = User::create([
            'name' => 'admin',
            'dni' => '12345678',
            'phone' => '123456789',
            'birthdate' => '1990-01-01',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678')
        ]);
        $superAdmin->assignRole('Super Admin');

        $visitor = User::create([
            'name' => 'no_admin',
            'dni' => '23456789',
            'phone' => '123456789',
            'birthdate' => '1990-01-01',
            'email' => 'no-admin@no-admin.com',
            'password' => Hash::make('12345678')
        ]);
        $visitor->assignRole('Visitor');

        $volunteer1 = User::create([
            'name' => 'voluntario1',
            'dni' => '87965472',
            'phone' => '945291643',
            'birthdate' => '1990-01-01',
            'email' => 'voluntario@voluntario.com',
            'password' => Hash::make('12345678')
        ]);
        $volunteer1->assignRole('Volunteer');

        $volunteer2 = User::create([
            'name' => 'voluntario2',
            'dni' => '87965473',
            'phone' => '965311540',
            'birthdate' => '1991-01-01',
            'email' => 'voluntario2@voluntario.com',
            'password' => Hash::make('12345678')
        ]);
        $volunteer2->assignRole('Volunteer');
    }
}
