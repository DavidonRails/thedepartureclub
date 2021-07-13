<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = DB::table('users')->where('email', '=', 'admin@hobojet.com')->get();
        
        if(count($user))
            return NULL;
        
        DB::table('users')->insert([
            'email' => 'admin@hobojet.com',
            'password' => bcrypt('loodloo'),
            'first_name' => 'HoboAdmin',
            'last_name' => 'Admin',
            'type' => 'web',
            'role' => 'admin',
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);


    }
}
