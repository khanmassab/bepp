<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(DB::table('categories')->count() == 0)
        {
            DB::table('categories')->insert([[
           
                'title' => 'Landscape Gardeners',
                'category_type' =>'trader',
                'status' => 1,
            ],
            [
                
                'title' => 'Plumber',
                'category_type' =>'trader',
                'status' => 1,
            ],
            [
              
                'title' => 'Electrician',
                'category_type' =>'trader',
                'status' => 1,
            ],
            [
              
                'title' => 'Builder',
                'category_type' =>'trader',
                'status' => 1,
            ],
            [
              
                'title' => 'Carpenters',
                'category_type' =>'trader',
                'status' => 1,
            ],
            [
              
                'title' => 'Painters and Decorators',
                'category_type' =>'trader',
                'status' => 1,
            ],
            [
                'title' => 'Architecture',
                'category_type' =>'trader',
                'status' => 0,
            ],
            [
                'title' => 'Wheel Alignment',
                'category_type' =>'garage',
                'status' => 1,
            ],
            [
                'title' => 'Windscreen',
                'category_type' =>'garage',
                'status' => 1,
            ],
            [
                'title' => 'Not Testing Service',
                'category_type' =>'garage',
                'status' => 1,
            ],
            [
                'title' => 'Electric Car Charger Installers',
                'category_type' =>'garage',
                'status' => 1,
            ],
            [
                'title' => 'BMW Service Centre',
                'category_type' =>'garage',
                'status' => 1,
            ],
            [
                'title' => 'Classic Car Restoration',
                'category_type' =>'garage',
                'status' => 1,
            ],
            [
                'title' => 'Acceident Repair',
                'category_type' =>'garage',
                'status' => 1,
            ],
            [
                'title' => 'Auto Air Conditioning',
                'category_type' =>'garage',
                'status' => 1,
            ],
            



            


        
        ]);
        }
       
        
    }
}
