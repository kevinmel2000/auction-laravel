<?php

namespace App\Libraries\VK;

use Session;

class VKClass
{
    public function __construct()
    {
    }

    private function getToken() {
        
    }

    public function getProfileAvatar ($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/users.get?fields=photo_100&v=5.50&user_id=' . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $avatarUrl = json_decode(curl_exec($ch), true)['response'][0]['photo_100'];
        curl_close($ch);

        return $avatarUrl;
    }

    public function searchUser($q, $limit = 20, $offset = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/users.search?fields=photo,screen_name&v=5.50&q=' . $q . '&offset=' . $offset . '&count=' . $limit . '&access_token=' . Session::get('access_token'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $users = json_decode(curl_exec($ch), true)['response'];
        curl_close($ch);

        return $users;
    }
}