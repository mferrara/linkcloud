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
        $links = [
            'https://domain.com/link1,anchor1',
            'https://domain.com/link2,anchor1',
            'https://domain.com/link3,anchor1',
            'https://domain.com/link4,anchor2',
            'https://domain.com/link5,anchor2',
            'https://domain.com/link6,anchor2',
            'https://domain.com/link7,anchor2',
            'https://domain.com/link8,anchor3',
            'https://domain.com/link9,anchor3',
            'https://domain1.com/link1,anchor3',
            'https://domain1.com/link2,anchor3',
            'https://domain1.com/link3,anchor3',
            'https://domain1.com/link4,anchor4',
            'https://domain1.com/link5,anchor4',
            'https://domain1.com/link6,anchor5',
            'https://domain1.com/link7,anchor5',
            'https://domain1.com/link8,anchor5',
            'https://domain1.com/link9,anchor5',
            'https://domain2.com/link1,anchor6',
            'https://domain2.com/link2,anchor6',
            'https://domain2.com/link3,anchor7',
            'https://domain2.com/link4,anchor7',
            'https://domain2.com/link5,anchor7',
            'https://domain2.com/link6,anchor8',
            'https://domain2.com/link7,anchor8',
            'https://domain2.com/link8,anchor8',
            'https://domain2.com/link9,anchor9',
        ];

        $user = \App\User::first();

        foreach($links as $link_string)
        {
            // Get anchor from string
            $exploded = explode(',', $link_string);

            if(count($exploded) == 2)
            {
                $link_string    = $exploded[0];
                $anchor         = $exploded[1];

                // Get domain name and path from the link
                $uri            = \League\Uri\Http::createFromString($link_string);
                $path           = $uri->getPath();
                $domain_name    = $uri->getHost();
                $domain         = \App\Domain::findOrCreate($domain_name, $user);

                // Determine the anchor
                $anchor         = \App\Anchor::findOrCreate($anchor, $user);

                $link = $user->links()->create([
                    'path'      => $path,
                    'domain_id' => $domain->id,
                    'anchor_id' => $anchor->id
                ]);

                echo 'Added link: '.$link->id.PHP_EOL;
                $array = $link->buildLink();
                echo 'URL: '.$array['href'].PHP_EOL;
                echo 'Anchor: '.$array['anchor'].PHP_EOL;
                echo 'HTML Link: '.$link->buildHTMLLink().PHP_EOL;
            }
            else
            {
                dd('Invalid imported link format.');
            }
        }

        var_dump($user->links()->get());
    }
}
