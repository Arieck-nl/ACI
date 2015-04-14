<?php
require_once '../init.php';

use Ivory\JsonBuilder\JsonBuilder;

$builder = new JsonBuilder();


$action = $_GET['action'];
$post = filter_var_array($_POST);


switch ($action) {
    case 'get':
        if (!isset($_POST['keyword'])) {
            exit;
        }
        get_links($database, $post, $builder);
        break;
    case 'feedback':
        break;
    case 'select':
        break;

    default:
}

function get_links($db, $post, $builder)
{

    $term = get_term($db, $post['keyword']);

    echo '<div style="color:#000; background-color:white"><pre>';
    print_r($term);
    echo '</pre>';
    die();

    if (!$term) {
        //invoeren in database
    }

    buildJson(array('my', 'master', 'is', 'Rick'), $builder);
}

function get_term($db, $keyword)
{

    return $db->get('aci_term', 'term', array('term' => $keyword));
}

function insert_link($db, $post)
{
    insert_term($db, $post);


}

function insert_term($db, $term)
{
    $id = $db->insert('aci_term', array('termID' => '', 'term' => $term));
    return $id;
}

