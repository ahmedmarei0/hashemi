<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::where('power', "ADMIN")->count();
        if ($users == 0) {
            //     \App\Models\User::create([
            //     'name' => "asd",
            //     'power' => "ADMIN",
            //     'username' => "asd123",
            //     'email' => "asd@gmail.com",
            //     'email_verified_at' => date("Y-m-d h:i:s"),
            //     'password' => bcrypt(123456),
            // ]);
            \App\Models\User::create([
                'name' => "ADMIN",
                'power' => "ADMIN",
                'username' => "admin",
                'email' => env('DEFAULT_EMAIL'),
                'email_verified_at' => date("Y-m-d h:i:s"),
                'password' => bcrypt(env('DEFAULT_PASSWORD')),
            ]);
        }

        for ($i = 0; $i < 20; $i++) {
            \App\Models\User::create([
                'name' => "user " . $i,
                'power' => "USER",
                'username' => "user" . $i,
                'email' => env('DEFAULT_EMAIL') . $i,
                'email_verified_at' => date("Y-m-d h:i:s"),
                'password' => bcrypt(env('DEFAULT_PASSWORD')),
            ]);
        }

    }
}
