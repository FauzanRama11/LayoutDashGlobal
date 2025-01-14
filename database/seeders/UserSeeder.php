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
        $gpc_user = User::create(
            ["username" => "gpc", 
            "name" => "Global Partnership & Collaboartion", 
            "email" => "",
            "password" => bcrypt("12345"),
        "is_active" => "True"]);
        $gpc_user->assignRole("gpc");

        $wdk_user = User::create(
            ["username" => "198404202008121005", 
            "name" => "	Iman Harymawan, S.E., M.BA., Ph.D.", 
            "email" => "",
            "password" => bcrypt("12345"),
        "is_active" => "True"]);

        $wdk_user->assignRole("wadek3");
    }
}
