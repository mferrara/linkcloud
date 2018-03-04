<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $links = [];

        // Max links added to the link pool
        $max        = 10000;
        if(App::environment() == 'production')
            $max    = 1000000;
        $extentions = ['.com', '.net', '.info', '.org', '.xyz', '.tk'];
        $paths      = ['link', 'page', 'user', 'view'];
        $anchors    = ['red', 'blue', 'green', 'yellow', 'orange', 'black', 'brown'];
        while(count($links) < $max)
        {
            // Build a random link
            $link = 'https://domain'.rand(0,99).$extentions[array_rand($extentions)].'/'.$paths[array_rand($paths)].rand(0,99).','.$anchors[array_rand($anchors)].rand(0,99).','.rand(1,15);
            // If it doesn't already exit, add it
            if( ! in_array($link, $links))
                $links[] = $link;
        }

        // Create a collection
        $links = collect($links);

        echo PHP_EOL;
        foreach(\App\User::all() as $user)
        {
            $insert_count = rand(250,1000);
            if(App::environment() == 'production')
                $insert_count = rand(2500,50000);
            $user->importLinks($links->take($insert_count));
            echo $user->id.' imported '.number_format($user->links()->count()).PHP_EOL;
        }
    }
}
