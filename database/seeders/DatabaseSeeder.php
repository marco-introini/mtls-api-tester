<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'mint.dev@pm.me',
            'password' => Hash::make('password'),
        ])->save();

        User::factory(2)->create();
    }
}
