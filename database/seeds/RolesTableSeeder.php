<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = new \App\Role();
        $role->name = "super_admin";
        $role->display_name = "Administrator";
        $role->description = "Role for admins";
        $role->save();

        $role = new \App\Role();
        $role->name = "view";
        $role->display_name = "User";
        $role->description = "Role for normal users";
        $role->save();

        $role = new \App\Role();
        $role->name = "edit";
        $role->display_name = "Edit User";
        $role->description = "Role for users who can edit";
        $role->save();
    }
}
