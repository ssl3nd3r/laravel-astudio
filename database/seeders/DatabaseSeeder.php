<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Project::factory(10)->create();
        // \App\Models\Timesheet::factory(10)->create();
        \App\Models\ProjectUser::factory(25)->create();
    }
}
