<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attachments;

class AttachmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 50; $i++) {
            Attachments::create([
                'user_id'=> 1,
                'lesson_id'=> rand(10,45),
                "title"=>"واجب رقم " .$i,
                'description'=> "واجب",
                'final_date_receive'=> \Carbon\Carbon::now(),
                'file' => $i % 2==0?  "123.pdf": "12345.jpg"
            ]);
        }
    }
}
