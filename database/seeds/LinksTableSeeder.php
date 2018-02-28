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
            'https://domain.com/link1,anchor1,3',
            'https://domain.com/link2,anchor1,4',
            'https://domain.com/link3,anchor1,5',
            'https://domain.com/link4,anchor2,6',
            'https://domain.com/link5,anchor2,2',
            'https://domain7.com/link6,anchor2,1',
            'https://domain7.com/link7,anchor2,3',
            'https://domain7.com/link8,anchor3,6',
            'https://domain7.com/link9,anchor3,4',
            'https://domain6.com/link1,anchor3,8',
            'https://domain6.com/link2,anchor3,7',
            'https://domain1.com/link3,anchor3,5',
            'https://domain4.com/link4,anchor4,3',
            'https://domain1.com/link5,anchor4,2',
            'https://domain1.com/link6,anchor5,7',
            'https://domain4.com/link7,anchor5,9',
            'https://domain1.com/link8,anchor5,4',
            'https://domain1.com/link9,anchor5,3',
            'https://domain4.com/link1,anchor6,5',
            'https://domain2.com/link2,anchor6,7',
            'https://domain2.com/link3,anchor7,2',
            'https://domain3.com/link4,anchor7,4',
            'https://domain5.com/link5,anchor7,3',
            'https://domain5.com/link6,anchor8,5',
            'https://domain5.com/link7,anchor8,1',
            'https://domain2.com/link8,anchor8,3',
            'https://domain2.com/link9,anchor9,6',
        ];

        foreach(\App\User::all() as $user)
        {
            $user->importLinks($links);
        }
    }
}
