<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CallBackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(DB::table('call_back_times')->count() == 0)
        {
            DB::table('call_back_times')->insert([[
           
                'call_time' => '09:00-11:00',
            ],
            [
                
                'call_time' => '11:00-13:00',
            ],
            [
              
                'call_time' => '13:00-15:00',
            ],
            [
              
                'call_time' => '15:00-17:00',
            ],
            [
              
                'call_time' => '17:00-18:00',
            ],
        
        ]);
        }
    }
}
