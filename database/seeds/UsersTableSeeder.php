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
            'points'                        => 10,
            'password'                      => bcrypt('password'),
            'last_read_announcements_at'    => Carbon::now(),
            'trial_ends_at'                 => Carbon::now()->addDays(Spark::trialDays()),
        ])->save();

        echo $user->email.' created!'.PHP_EOL;
    }
}
