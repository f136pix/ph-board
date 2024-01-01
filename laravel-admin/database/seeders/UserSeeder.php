<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'surname' => 'Admin',
            'email' => 'Admin@admin.com',
            'role_id' => 1,
            'password' => '123'
        ]);

        User::factory()->create([
            'name' => 'Editor',
            'surname' => 'Editor',
            'email' => 'Editor@editor.com',
            'role_id' => 2,
            'password' => '123'
        ]);

        User::factory()->create([
            'name' => 'Viewer',
            'surname' => 'Viewer',
            'email' => 'Viewer@viewer.com',
            'role_id' => 3,
            'password' => '123'
        ]);
    }
}
