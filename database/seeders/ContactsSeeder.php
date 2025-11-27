<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ContactsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('contact_messages')->insert([
            [
                'subject' => 'Enquire about Getting Started with Laravel',
               
                'message' => 'This is a comprehensive guide to Laravel basics...',
                'name' => 'Mohammed',
                'email' => 'majd@gmail.com',
                'status'=>'reviewed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject' => 'Enquire about your services',
               
                'message' => 'are there real ',
                'name' => 'Mohammed',
                'email' => 'mahmoud@gmail.com',
                'status'=>'reviewed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subject' => 'Enquire about Nextjs & tasks',
               
                'message' => 'are they good enough',
                'name' => 'Mohammed',
                'email' => 'ahmad@gmail.com',
                'status'=>'new',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
