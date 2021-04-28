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
        // Object of App\Models\User class
        User::factory()->johnDoe()->create();

        // Eloquent Collection
        User::factory()->count(20)->create();
    }
}
