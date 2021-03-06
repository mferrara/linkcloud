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

        $domains = [
            'google.com',
            'bing.com',
            'facebook.com',
            'twitter.com',
            'youtube.com',
            'instagram.com',
            'linkedin.com',
            'pinterest.com',
            'wordpress.com',
            'apple.com',
            'adobe.com',
            'amazon.com',
            'flickr.com',
            'microsoft.com',
            'yahoo.com',
            'reddit.com'
        ];

        $paths      = ['link', 'page', 'user', 'view'];
        $anchors    = ['red', 'blue', 'green', 'yellow', 'orange', 'black', 'brown'];
        echo PHP_EOL.'Building link pool...'.PHP_EOL;
        while(count($links) < $max)
        {
            // Build a random link
            $link       = 'https://'.$domains[array_rand($domains)].'/'.$paths[array_rand($paths)].rand(0,99).','.$anchors[array_rand($anchors)].rand(0,99).','.rand(1,15);
            $links[]    = $link;

            if(count($links) % 1000 == 0)
                echo count($links).' links...'.PHP_EOL;
        }

        // Create a collection
        $links = collect($links);

        echo PHP_EOL;
        foreach(\App\User::all() as $user)
        {
            $insert_count = rand(250,1000);
            if(App::environment() == 'production')
                $insert_count = rand(250,3000);
            $user->importLinks($links->take($insert_count));
            echo $user->id.' imported '.number_format($user->links()->count()).PHP_EOL;
        }
    }
}
