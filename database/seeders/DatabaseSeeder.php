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
        $this->call([
            TagSeeder::class,
            UsersSeeder::class,
            SettingsSeeder::class,
            LaratrustSeeder::class,
            AttachSuperAdminPermissions::class,
            CoursesSeeder::class,
            LessonsSeeder::class,
            AttendantsSeeder::class,
            AttachmentsSeeder::class,
        ]);
    }
}

