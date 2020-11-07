<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Login user', 'Editor', 'Administrator'];
        foreach ($roles as $name) {
            $role = new Role();
            $role->name = $name;
            $role->save();
        }
    }
}
