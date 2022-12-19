<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lessions;

class LessonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       for ($i=0; $i < 50; $i++) {
         Lessions::create([
            'user_id'=>1,
            'course_id'=> rand(1,19),
            "title"=>"درس رقم " .$i,
            "description"=>"درس رقم " .$i,
            "video"=> $i % 2==0?  "https://www.youtube.com/watch?v=uLxG_g4xuY0&t=6s&ab_channel=%D8%A7%D8%AD%D9%85%D8%AF%D9%87%D8%A7%D8%B4%D9%85%D8%A7%D9%84%D9%87%D8%A7%D8%B4%D9%85%D9%8A": "https://www.youtube.com/watch?v=CodOHwBJxos&ab_channel=%D8%A7%D8%AD%D9%85%D8%AF%D9%87%D8%A7%D8%B4%D9%85%D8%A7%D9%84%D9%87%D8%A7%D8%B4%D9%85%D9%8A"
        ]);
       }
    }
}
