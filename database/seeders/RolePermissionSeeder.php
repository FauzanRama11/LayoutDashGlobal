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
        Permission::create(["name" => "student-inbound"]);
        Permission::create(["name" => "student-outbound"]);
        Permission::create(["name" => "staff-inbound"]);
        Permission::create(["name" => "staff-outbound"]);

        Role::create(["name" => "gmp"]);
        Role::create(["name" => "fakultas"]);
        Role::create(["name" => "mahasiswa"]);

        $rolegmp = Role::findByName("gmp");
        $rolegmp-> givePermissionTo("student-inbound");
        $rolegmp-> givePermissionTo("student-outbound");

        $rolefa = Role::findByName("fakultas");
        $rolefa-> givePermissionTo("student-inbound");
        $rolefa-> givePermissionTo("student-outbound");
        $rolefa-> givePermissionTo("staff-inbound");
        $rolefa-> givePermissionTo("staff-outbound");
    }
}
