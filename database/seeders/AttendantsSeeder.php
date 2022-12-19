<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendants;
use App\Models\Sheets;

class AttendantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 50; $i++) {
            Attendants::create([
                'user_id' => $i % 2==0? 1: 2,
                'lesson_id' => rand(10,49),
                'count' => 1,
            ]);
        }
        for ($i=0; $i < 50; $i++) {
            Sheets::create([
                'user_id' => $i % 2==0? 1: 2,
                'lesson_id' => rand(10,49),
                'file' => $i % 2==0?  "12345.jpg": "1234.pdf"
            ]);
        }
    }
}
