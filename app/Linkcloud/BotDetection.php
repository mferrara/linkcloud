<?php


namespace App\Linkcloud;


use App\Ip;
use App\UserAgent;

class BotDetection
{

    /**
     * Determine if the API request is from a bot
     *
     * @param $ip_address
     * @param $user_agent
     * @return bool
     */
    public static function isThisABot($ip_address, $user_agent)
    {
        // Get or create the IP/UserAgent models
        $ip = Ip::findOrCreate($ip_address);
        $ua = UserAgent::findOrCreate($user_agent);

        // Determine if they're a bot by IP address/host
        if(     mb_stristr($ip->host, 'google')
            ||  mb_stristr($ip->host, '1e100.net'))
            return true;

        // Determine if they're a bot by UserAgent
        if(mb_stristr($ua->text, 'googlebot'))
            return true;

        return false;
    }
}
