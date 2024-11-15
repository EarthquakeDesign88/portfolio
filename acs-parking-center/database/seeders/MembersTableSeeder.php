<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\MemberFactory;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Member::factory()->count(20)->create();
    }
}
