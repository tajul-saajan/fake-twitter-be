<?php

namespace Database\Seeders;

use App\Models\Tweet;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

class TweetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Generator $faker): void
    {
        $userIds = User::all()->pluck('id')->toArray();
        Tweet::factory()
            ->count(5)
            ->create([
                'posted_by' => $faker->randomElement($userIds),
            ]);
    }
}
