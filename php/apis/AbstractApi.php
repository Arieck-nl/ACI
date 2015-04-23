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
abstract class AbstractApi
{

    const HTTP_OK = 200;

    protected $_url_base;
    protected $_url_format;
    protected $_api_key;
    protected $_api_name;


    /**
     * @param string $url
     * @param string $api_key
     */
    public function  __construct($api_name, $url_base, $url_format = null, $api_key = null)
    {
        $this->_api_name = $api_name;
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
    protected abstract function get_link_array($keyword, $result_limit = 10);

    /**
     * return processed array of link words
     *
     * @param string $keyword
     * @param int $result_limit
     * @return array
     */
    public function get_api_array($keyword, $result_limit = 10)
    {

        $array = $this->get_link_array($keyword, $result_limit);

        if(!is_array($array)){
            return false;
        }

        if (DEBUG) {
            foreach ($array as &$link) {
                $link .= '(' . $this->_api_name . ')';
            }
        }

        return $array;
    }

    /**
     * Creates api url
     *
     * @param string $keyword
     * @return string
     */
    protected function create_url($keyword, $order = array('base', 'key', 'keyword', 'format'))
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
        $data = json_decode($response, true);


        return $data;
    }

    /**
     * filter out words that occure as much or more as given int $min_occ
     *
     * @param string $text
     * @param int $min_occ
     * @return array
     */
    protected function occurence_filter($text, $min_occ)
    {

        //filter out proper nouns

//        $this->_proper_noun->acronyms(true);
//        $this->_proper_noun->possible(true);
//        $this->_proper_noun->multi_words(true);
//        $nouns_array = $this->_proper_noun->get($text);
//        $nouns = implode(',', $nouns_array);

        //generate array of words, count occurences, then sort on occurences

        $words = (array_count_values(str_word_count($text, 1)));

        if (is_array($words)) {

            //filter out any words with less than OCC occurences
            foreach ($words as $key => $value) {
                if ($value < $min_occ) {
                    unset($words[$key]);
                }
            }
        }

        return $words;
    }

}

