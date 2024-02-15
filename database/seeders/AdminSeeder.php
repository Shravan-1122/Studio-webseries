<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a sample admin user
        Admin::create([
            'email' => 'developer.admin@gmail.com',
            'password' =>'password', // Hash the password
        ]);

        // You can add more admin users as needed...
    }
}