<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            ['name' => 'Kesehatan',          'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pengembangan Diri',  'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Produktivitas',      'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keuangan',           'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kebiasaan Buruk',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
