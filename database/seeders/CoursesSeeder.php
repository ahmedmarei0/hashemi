<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Courses;
use App\Models\Subjects;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *          php artisan db:seed --class=DriverSeeder
     *
     * @return void
     */
    public function run()
    {
        Subjects::create([
            'title' => "الكيميـــاء",
            'description' => "شرح مادة الكيميـــاء",
            'user_id' => 1,
        ]);
        Subjects::create([
            'title' => "الفيزيـــاء",
            'description' => "شرح مادة الفيزيـــاء",
            'user_id' => 1,
        ]);
        for ($i=0; $i < 10; $i++) {
            Courses::create([
                'subject_id' => 1,
                'title' => "الفصــل رقم ".$i,
                'description' => "الفصــل رقم ".$i,
                'user_id' => 1,
            ]);
        }
        for ($i=0; $i < 10; $i++) {
            Courses::create([
                'subject_id' => 2,
                'title' => "الفصــل رقم ".$i,
                'description' => "الفصــل رقم ".$i,
                'user_id' => 1,
            ]);
        }
    }
}
