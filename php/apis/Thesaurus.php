<?php
/**
 * Created by PhpStorm.
 * User: Rick
 * Date: 14-04-15
 * Time: 10:23
 */

namespace Apis;


class Thesaurus extends AbstractApi
{
    const API_NAME = 'Thesaurus';
    const URL_BASE = 'http://words.bighugelabs.com/api/2/';
    const URL_FORMAT = 'json';
    const API_KEY = 'c5db994845995315d4505ac9085c4d20';

    function __construct()
    {
        parent::__construct(self::API_NAME, self::URL_BASE, self::URL_FORMAT, self::API_KEY);
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
        $url = $this->create_url(urlencode($keyword));
        $data = $this->get_api_data($url);

        if (!$data) {
            return false;
        }

        if (!isset($data['noun']['syn']) && isset($data['verb']['syn'])) {
            return $data['verb']['syn'];
        } elseif (!isset($data['verb']['syn']) && isset($data['noun']['syn'])) {
            return $data['noun']['syn'];
        }

        $link_array = array_merge($data['noun']['syn'], $data['verb']['syn']);

        return $link_array;

    }

}