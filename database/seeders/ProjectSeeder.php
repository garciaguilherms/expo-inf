<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('projects')->insert([
            'title' => Str::random(10),
            'description' => Str::random(50),
            'image' => 'https://br.godaddy.com/blog/wp-content/uploads/O-Que-E-Um-Codigo-fonte.jpg',
            'visibility' => 1,
            'section_id' => rand(1, 4),
            'author_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
