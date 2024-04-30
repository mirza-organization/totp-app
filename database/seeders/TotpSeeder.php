<?php

namespace Database\Seeders;

use App\Models\Totp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TotpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Totp::factory()->count(5)->create();
    }
}
