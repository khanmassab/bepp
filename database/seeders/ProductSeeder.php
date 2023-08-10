<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(DB::table('products')->count() == 0)
        {
            $values = ['Breakdown insurance','Car insurance','Commercial fleet insurance',
            'Commercial property insurance','Classic car insurance','Goods in transit insurance',
            'Holiday home insurance','Home worker insurance','Home insurance',
            'Landlords buildings and contents insurance',
            'Liability insurance','Modified vehicle insurance','Motorcycle insurance','Office insurance',
            'Other commercial insurance',
            'Professional indemnity insurance', 'Tools insurance','Shop insurance','Van insurance'
            ];

            foreach ($values as $value) {
            Product::create(['title' => $value]);
            }
        }
        
    }
}
