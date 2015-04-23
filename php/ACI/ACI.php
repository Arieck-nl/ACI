<?php
/**
 * Created by PhpStorm.
 * User: Rick
 * Date: 14-04-15
 * Time: 14:58
 */

namespace ACI;


use Apis\AbstractApi;
use Apis\Faroo;
use Apis\Thesaurus;
use Apis\Webhose;
use Ivory\JsonBuilder\JsonBuilder;

class ACI
{
    private $_apis = array();
    private $_db;
    private $_builder;

    const MIN_LINKS = 10;
    const MIN_CHAR = 3;
    const MAX_LINKS = 25;
    const MIN_VOTES = 3;
    const VOTES_RATIO = 1.5;

    public function __construct($database)
    {
        $this->_builder = new JsonBuilder();
        $this->_db = $database;

        // initiate API's
        $this->_apis[] = new Thesaurus();
//        $this->_apis[] = new Webhose();
        $this->_apis[] = new Faroo();

    }

    /**
     * get json with all the links for given keyword, xhr calls this
     *
     * @param string $keyword
     */
    public function get_json($keyword)
    {
        $keyword_id = $this->insert_term($keyword);

        $links = $this->_get_DB_links($keyword_id);
        $link_count = count($links);

        //if not enough database results, call APIs
        if ($link_count < self::MIN_LINKS) {
            $api_links = $this->_get_API_links($keyword);
        }


        if (!empty($api_links)) {


            shuffle($api_links);
            $unique_links = array_unique($api_links);

            $api_count = count($unique_links);
            for ($i = 0; ($i < $api_count && $i < (self::MAX_LINKS - $link_count)); $i++) {
                if (!isset($unique_links[$i])) {
                    continue;
                }
                if (strtolower($unique_links[$i]) == strtolower($keyword)) {
                    continue;
                }
                array_push($links, $unique_links[$i]);
            }
        }

        //if total result is less than 15, no mindmap available
        if (count($links) < self::MIN_LINKS) {
            $this->json_response(array('status' => 'no_result'));
        }

        $complete_links = array();
        foreach ($links as $link) {
            $result = $this->_db->get('aci_term', array('termID'), array('term' => $link));
            $complete_links[] = array('id' => $result['termID'], 'term' => $link);
        }

        $this->json_response(
            array(
                'status' => 'success',
                'keyword' => array('content' => $keyword, 'id' => $keyword_id),
                'links' => $complete_links
            ));
    }

    private function _get_DB_links($keyword_id)
    {
        $links = array();


        $query = 'SELECT term2,term
                  FROM aci_link AS link
                  INNER JOIN aci_term AS term ON link.term2 = term.termID
                  WHERE link.term1 = %d
                  AND (downvotes + upvotes) > %d
                  AND (upvotes / downvotes) > %f
                  AND blacklist = 0
                  ORDER BY (upvotes / downvotes)
                  LIMIT %d';


        $result = $this->_db
            ->query(sprintf($query, $keyword_id, self::MIN_VOTES, self::VOTES_RATIO, self::MAX_LINKS / 2))
            ->fetchAll(\PDO::FETCH_KEY_PAIR);


        if (!empty($result)) {
            $links = $result;
        }

        return $links;
    }

    /**
     * loops through every API to get links according to keyword
     *
     * @param string $keyword
     * @return array
     */
    private function _get_API_links($keyword)
    {
        $links = array();

        foreach ($this->_apis as $api) {
            $api_array = $api->get_api_array($keyword);


            if (!is_array($api_array)) {
                continue;
            }
            foreach ($api_array as $link) {
                $links[] = $link;
            }
        }

        //in database stoppen
        $clean_links = $links;
        if (!DEBUG) {
            $this->insert_links($keyword, $links);
            $clean_links = $this->blacklist_filter($links);
        }

        return $clean_links;
    }


// @TODO Nieuwe API's implementeren, Ophalen links uit database, nieuwe verbanden opslaan, buildJSON gebruiken, multidimensionale json, niet meer dan 15 db records, kijken of xhr bestand veilig staat

    /**
     * insert acquired links
     *
     * @param string $keyword
     * @param array $links
     * @return array
     */
    private function insert_links($keyword, $links)
    {
        $keyword_id = $this->insert_term($keyword);

        foreach ($links as $link) {
            $link_id = $this->insert_term($link);

            $result = $this->_db->insert('aci_link', array('term1' => $keyword_id, 'term2' => $link_id));
        }
        return true;

    }

    /**
     * insert single term
     *
     * @param string $term
     * @return int
     */
    private function insert_term($term)
    {
        $blacklist = (strlen($term) < self::MIN_CHAR) ? true : false;

        $id = $this->_db->insert('aci_term', array('termID' => '', 'term' => $term, 'blacklist' => $blacklist));

        if ((int)$id == 0) {
            $result = $this->_db->get('aci_term', array('termID'), array('term' => $term));
            $id = $result['termID'];
        }
        return $id;
    }

    /**
     * returns a json file according to supplied array
     *
     * @param array $data
     * @param int $status
     */
    public function json_response($data, $status = 200)
    {
        $json = '';
        if (is_array($data)) {
            $json = $this->_builder
                ->setValues($data)
                ->build();
        }

        if (!is_numeric($status)) {
            $status = 500;
        }

        header("http/1.0 " . $status);
        header("Content-Type: application/json; charset=utf-8");
        echo $json;
        exit;
    }

    private function blacklist_filter($term_array)
    {

        if (!is_array($term_array)) {
            return false;
        }

        $clean_array = array();

        foreach ($term_array as $term) {
            $result = $this->_db->get('aci_term', array('term', 'blacklist'), array('term' => $term));

            if (!(bool)$result['blacklist']) {
                $clean_array[] = $result['term'];
            }
        }

        return $clean_array;
    }

}