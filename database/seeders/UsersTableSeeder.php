<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ask the user the number of users to be created, at least 1
        $usersCount = max((int) $this->command->ask('How many users would you like?', 20), 1);
        // Object of App\Models\User class
        User::factory()->johnDoe()->create();

        // Eloquent Collection
        User::factory()->count($usersCount)->create();
    }
}
