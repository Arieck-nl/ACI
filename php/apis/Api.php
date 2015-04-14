<?php
/**
 * Created by PhpStorm.
 * User: Rick
 * Date: 14-04-15
 * Time: 10:03
 */

namespace Apis;

/**
 * Class Api
 * @package Apis
 */
abstract class Api
{

    const HTTP_OK = 200;

    protected $_url_base;
    protected $_url_format;
    protected $_api_key;


    /**
     * @param string $url
     * @param string $api_key
     */
    public function  __construct($url_base, $url_format = null, $api_key = null)
    {
        $this->_url_base = $url_base;
        $this->_url_format = $url_format;
        $this->_api_key = $api_key;
    }

    /**
     * Return array of terms associated with given keyword
     *
     * @param string $keyword
     * @param int $result_limit
     * @return array
     */
    public abstract function get_link_array($keyword, $result_limit);

    /**
     * Creates api url
     *
     * @param string $keyword
     * @return string
     */
    protected function create_url($keyword)
    {
        $url = $this->_url_base;
        $url .= !is_null($this->_api_key) ? $this->_api_key . '/' : '';
        $url .= $keyword . '/';
        $url .= !is_null($this->_url_format) ? $this->_url_format : '';

        return $url;
    }

    protected function get_api_data($url)
    {
        $ch = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ];

        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return false;
        }

        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($info != self::HTTP_OK) {
            return false;
        }


        $data = json_decode($response);
        return $data;
    }

}