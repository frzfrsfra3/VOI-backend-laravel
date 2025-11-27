<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class Comments extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('comments')->insert([
            [
              
                'body' => ' comment bout This is a comprehensive guide to Laravel basics...',
                'user_id' => 1,
                'article_id'=>1,
                'approved'=>1,
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
             
                'body' => 'comments bout  how to master Eloquent relationships...',
                'user_id' => 1,
                'article_id'=>2,
                'approved'=>1,
                
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
            
                'body' => 'A complete tutorial on RESTful API development...',
               
          
                'user_id' => 2,
                'article_id'=>3,
                'approved'=>1,
                
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
