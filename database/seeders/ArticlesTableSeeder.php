<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('articles')->insert([
            [
                'title' => 'Getting Started with Laravel',
                'slug'=>'Getting-Started-with-Laravel',
                'content' => 'This is a comprehensive guide to Laravel basics...',
                'author_id' => 1,
                'is_published'=>1,
                'category_id'=>1,
                'comments_enabled'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Advanced Eloquent Techniques',
                'slug'=>'Advanced-Eloquent-Techniques',
                'content' => 'Learn how to master Eloquent relationships...',
                'author_id' => 2,
                'is_published'=>1,
                'comments_enabled'=>1,
                'category_id'=>2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Building APIs with Laravel',
                'slug'=>'Building-APIs-with-Laravel',
                'content' => 'A complete tutorial on RESTful API development...',
                'author_id' => 1,
                'is_published'=>1,
                'category_id'=>3,
                'comments_enabled'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Getting Started with Laravel333',
                'slug'=>'Getting-Started-with-Laravel333',
                'content' => 'This is a comprehensive guide to Laravel basics...',
                'author_id' => 1,
                'is_published'=>1,
                'category_id'=>1,
                'comments_enabled'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Advanced Techniques',
                'slug'=>'Advanced-Techniques',
                'content' => 'Learn how to master Eloquent relationships...',
                'author_id' => 2,
                'is_published'=>1,
                'comments_enabled'=>1,
                'category_id'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Building Compoonents with Nextjs & React Typescript',
                'slug'=>'Building-with-React',
                'content' => 'A complete tutorial on Frontend development...',
                'author_id' => 1,
                'is_published'=>1,
                'category_id'=>2,
                'comments_enabled'=>1,
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);
    }
}