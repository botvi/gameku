<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama_jalur' => 'Superadmin',
            'kuansing_poin' => 1000,
            'role' => 'admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}