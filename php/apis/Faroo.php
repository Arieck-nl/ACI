<?php
/**
 * Created by PhpStorm.
 * User: Rick
 * Date: 15-04-15
 * Time: 11:21
 */

namespace Apis;


class Faroo extends AbstractApi {

    const HEADER = "MindmapRobot/1.0 (http://mindmaprobot.com/; info@arieck.nl)";

    const API_NAME = 'Faroo';
    const URL_BASE = 'http://www.faroo.com/api?q=%s&key=%s&src=news';
    const URL_FORMAT = 'json';
    const API_KEY = 'PvFST1tS6A73yqF5Pps@K9tCRBg_';
    const OCC = 3;

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
        $url = sprintf(self::URL_BASE, urlencode($keyword), self::API_KEY);

        $data = $this->get_api_data($url);

        if (!$data) {
            return false;
        }

        $text = implode(' ', array_column($data['results'], 'kwic'));
        $text .= implode(' ', array_column($data['results'], 'title'));

        $words = $this->occurence_filter($text, self::OCC);

        return array_keys($words);
    }
}