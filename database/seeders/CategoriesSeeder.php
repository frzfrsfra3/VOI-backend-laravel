<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categories')->insert([
            [  
                'name'=>'health',
                'slug'=>'Health',
                'description'=>'good topic for health'
            ],
            [  
                'name'=>'sport',
                'slug'=>'sport',
                'description'=>'exciting topic for Sports'
            ],
            [  
                'name'=>'News',
                'slug'=>'News',
                'description'=>'Real & Urgent News'
            ]

        
        
        ]);
    }
}
