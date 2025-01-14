<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(["name" => "telaah-naskah"]);
        // Permission::create(["name" => "student-outbound"]);
        // Permission::create(["name" => "staff-inbound"]);
        // Permission::create(["name" => "staff-outbound"]);

        Role::create(["name" => "gpc"]);
        Role::create(["name" => "wadek3"]);
    

        $rolegpc = Role::findByName("gmp");
        $rolegpc-> givePermissionTo("telaah-naskah");

        $rolewdk = Role::findByName("fakultas");
        $rolewdk-> givePermissionTo("telaah-naskah");
    }
}
