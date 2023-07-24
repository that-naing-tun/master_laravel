<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCount = max((int)$this->command->ask('How many users would you like?', 20), 1);
        User::factory()->suspended()->create();
        User::factory($userCount)->create();
    }
}
