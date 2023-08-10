<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(DB::table('users')->where('role_id',1)->count() == 0)
        {
            User::create([
                'name' =>'Admin',
                'email' => 'admin@gmail.com',
                'role_id' => '1',
                'password' => Hash::make('12345678'),
            ]);
        }
       
    }
}
