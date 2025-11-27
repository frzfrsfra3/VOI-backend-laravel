<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class Roleseeder extends Seeder

{
    /**
     * Run the database seeds.
     */
 

public function run()
{
    $adminRole  = Role::firstOrCreate(['name' => 'admin']);
    $editorRole = Role::firstOrCreate(['name' => 'editor']);

    $admin = User::first();
    $admin->assignRole($adminRole);

    Permission::firstOrCreate(['name' => 'articles.view']);
    Permission::firstOrCreate(['name' => 'articles.create']);
    Permission::firstOrCreate(['name' => 'articles.update']);
    Permission::firstOrCreate(['name' => 'articles.delete']);
    
    Permission::firstOrCreate(['name' => 'categories.manage']);
    Permission::firstOrCreate(['name' => 'comments.moderate']);
    Permission::firstOrCreate(['name' => 'messages.review']);
    
}

 
}
