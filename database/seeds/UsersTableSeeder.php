<?php

use Illuminate\Database\Seeder;
use Laravel\Spark\Spark;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Spark::user();

        $user->forceFill([
            'name'                          => 'Mike Ferrara',
            'email'                         => 'mferrara@gmail.com',
            'points'                        => 1000,
            'password'                      => bcrypt('password'),
            'last_read_announcements_at'    => Carbon::now(),
            'trial_ends_at'                 => Carbon::now()->addDays(Spark::trialDays()),
        ])->save();

        echo $user->email.' created!'.PHP_EOL;

        $faker = Faker\Factory::create();

        $max_users = config('linkcloud.seeded_user_count');
        while(\App\User::count() <= $max_users)
        {
            $user = Spark::user();

            $user->forceFill([
                'name'                          => $faker->name,
                'email'                         => $faker->email,
                'points'                        => 1000,
                'password'                      => bcrypt('password'),
                'last_read_announcements_at'    => Carbon::now(),
                'trial_ends_at'                 => Carbon::now()->addDays(Spark::trialDays()),
            ])->save();

            echo $user->email.' created!'.PHP_EOL;
        }
    }
}
