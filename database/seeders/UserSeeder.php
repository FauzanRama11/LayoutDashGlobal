<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gmp_user = User::create(
            ["username" => "gmp", 
            "name" => "Global Mobility Program", 
            "email" => "",
            "password" => bcrypt("12345"),
        "is_active" => "True"]);
        $gmp_user->assignRole("gmp");

        $fa_user = User::create(
            ["username" => "fa_ftmm", 
            "name" => "Fakultas Teknologi Maju dan Multidisiplin", 
            "email" => "",
            "password" => bcrypt("12345"),
        "is_active" => "True"]);

        $fa_user->assignRole("fakultas");
    }
}
