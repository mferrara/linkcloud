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
        $user = \App\User::first();

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

        $user->importLinks($links);

        var_dump('User has '.$user->links()->count().' links');
    }
}
