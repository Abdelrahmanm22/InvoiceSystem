<?php

namespace Database\Seeders;

use App\Models\Safe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SafeRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Safe::create([
            'money'=>0,
        ]);
    }
}
