<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PlanoSeeder::class,
            SubscriberCategorySeeder::class,
            ServiceSeeder::class,
            SubscriberCategoryRestrictedServiceSeeder::class,
            CitySeeder::class,
            UserSeeder::class,
            ProfileSeeder::class,
            ProfilePhysicalAttributeSeeder::class,
            ProfileAvailabilitySeeder::class,
            ProfilePricingSeeder::class,
            ProfileCommentSeeder::class,
            ProfileReportSeeder::class,
            ProfileImageSeeder::class,
            SubscriptionRequestSeeder::class,
            FaqSeeder::class,
            SubscriptionSeeder::class,
            SubscriptionHistorySeeder::class,
        ]);
    }
}
