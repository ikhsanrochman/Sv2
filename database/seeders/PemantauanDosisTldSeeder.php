<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Project;
use App\Models\PemantauanDosisTld;
use Carbon\Carbon;

class PemantauanDosisTldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();

        foreach ($users as $user) {
            foreach ($projects as $project) {
                // Generate data for each month in 2024
                for ($month = 1; $month <= 12; $month++) {
                    PemantauanDosisTld::create([
                        'project_id' => $project->id,
                        'user_id' => $user->id,
                        'dosis' => rand(1, 100) / 10, // Random dose between 0.1 and 10
                        'tanggal_pemantauan' => Carbon::create(2024, $month, 15), // Set to 15th of each month
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
