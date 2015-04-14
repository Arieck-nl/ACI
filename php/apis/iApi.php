<?php
/**
 * Created by PhpStorm.
 * User: Rick
 * Date: 14-04-15
 * Time: 10:03
 */

interface iApi {

    /**
     * Return array of terms associated with given keyword
     *
     * @param string $keyword
     * @param int $result_limit
     * @return array
     */
    public function getLinkArray($keyword, $result_limit);

}