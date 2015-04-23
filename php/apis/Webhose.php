<?php
/**
 * Created by PhpStorm.
 * User: Rick
 * Date: 15-04-15
 * Time: 13:36
 */

namespace Apis;


class Webhose extends AbstractApi
{
    const API_NAME = 'Webhose';
    const URL_BASE = 'https://webhose.io/search?token=%s&format=json&q=%s&language=english&size=10';
    const URL_FORMAT = 'json';
    const API_KEY = 'ee1c3d9f-332f-4ac0-8800-5d5c6c658089';

    const OCC = 15;


    function __construct()
    {
        parent::__construct(self::API_NAME, self::URL_BASE);
    }


    /**
     * Return array of terms associated with given keyword
     *
     * @param string $keyword
     * @param int $result_limit
     * @return array
     */
    protected function get_link_array($keyword, $result_limit = 10)
    {
        $url = sprintf(self::URL_BASE, self::API_KEY, urlencode($keyword));

        //$data = json_decode(file_get_contents("/Users/Rick/Dropbox/Mediatechnologie/htdocs/ACI/php/Apis/dummy.json"), true);

        $data = $this->get_api_data($url);

        if (!$data) {
            return false;
        }

        $text = implode(' ', array_column($data['posts'], 'text'));

        $words = $this->occurence_filter($text, self::OCC);

        return array_keys($words);
    }
}