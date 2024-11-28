<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        User::create([
            'name' => 'admin',
            'email' => 'admin@local',
            'password' => Hash::make('12345678'),
        ])->assignRole('admin');

        User::create([
            'name' => 'admin puskesmas',
            'email' => 'puskesmas@local',
            'password' => Hash::make('12345678'),
        ])->assignRole('admin puskesmas');

        User::create([
            'name' => 'orang tua',
            'email' => 'ortu@local',
            'password' => Hash::make('12345678'),
        ])->assignRole('orang tua');
    }
}
