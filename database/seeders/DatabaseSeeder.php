<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Component;
use App\Models\Contract;
use App\Models\County;
use App\Models\Disposition;
use App\Models\Expense;
use App\Models\InventoryUnit;
use App\Models\StorageLocation;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Vendor::factory()->count(50)->create();
        Certification::factory()->count(1000)->create();
        Component::factory()->count(1000)->create();
        County::factory()->count(50)->create();
        Contract::factory()->count(50)->create();
        Expense::factory()->count(50)->create();
        InventoryUnit::factory()->count(1500)->create();
        Disposition::factory()->count(50)->create();
        StorageLocation::factory()->count(50)->create();
    }
}
