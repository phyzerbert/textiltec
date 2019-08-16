<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin1',
            'password' => bcrypt('123456'),
            'first_name' => 'Jairo',
            'last_name' => 'Alberto',
        ]);
    }
}
