<?php
/**
 * Created by PhpStorm.
 * User: Rick
 * Date: 14-04-15
 * Time: 10:23
 */

namespace Apis;


class Thesaurus extends Api
{
    const URL_BASE = 'http://words.bighugelabs.com/api/2/';
    const URL_FORMAT = 'json';
    const API_KEY = 'c5db994845995315d4505ac9085c4d20';

    function __construct()
    {
        parent::__construct(self::URL_BASE, self::URL_FORMAT, self::API_KEY);
    }


    /**
     * Return array of terms associated with given keyword
     *
     * @param string $keyword
     * @param int $result_limit
     * @return array
     */
    public function get_link_array($keyword, $result_limit = 10)
    {

        $url = $this->create_url($keyword);
        $data = $this->get_api_data($url);

        if(!$data){
            return false;
        }

    }

}