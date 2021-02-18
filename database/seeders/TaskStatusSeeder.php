<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* \App\Models\TaskStatus::factory(5)->create(); */
        DB::table('task_statuses')->insert([
            ['name' => 'New'],
            ['name' => 'Work in progress'],
            ['name' => 'Testing'],
            ['name' => 'Done']
        ]);

        DB::table('task_statuses')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
