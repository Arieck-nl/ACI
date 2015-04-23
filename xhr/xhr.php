<?php
require_once '../init.php';

use ACI\ACI;

$action = $_GET['action'];
$post = $_POST;

$ACI = new ACI($database);

switch ($action) {
    case 'get':
        if (!isset($_POST['keyword'])) {
            $ACI->json_response(array('error' => 'missing_parameter'), 400);
        }

//        header("http/1.0 200");
//        header("Content-Type: application/json; charset=utf-8");
//        echo file_get_contents('../php/ACI/dummy.json');
//        exit;

        $ACI->get_json($post['keyword']);
        break;
    case 'feedback':
        if (!isset($_POST['link_id']) || !isset($_POST['vote']) || !isset($_POST['term'])) {
            $ACI->json_response(array('error' => 'missing_parameter'), 400);
        }
        insert_feedback($ACI, $database, $post);
        break;
    case 'insert':
        break;

    default:
}


/**
 * insert given feedback
 *
 * @param ACI $ACI
 * @param medoo $db
 * @param array $post
 */
function insert_feedback($ACI, $db, $post)
{
    $term_ids = explode('-', $post['link_id']);


    switch ($post['vote']) {
        case 'upvote':
            $update = array('upvotes[+]' => 1);
            break;
        case 'downvote':
            $update = array('downvotes[+]' => 1);
            break;
        case 'report':
            $insert = array(
                'termID' => (int)$term_ids[1],
                'term' => $post['term']
            );
            $result = $db->insert('aci_possible_blacklist', $insert);
            break;
        default:
            $ACI->json_response(array('error' => 'missing_parameter'), 400);

    }
    if ($post['vote'] != 'report') {
        $where = array(
            'AND' => array(
                'term1' => (int)$term_ids[0],
                'term2' => (int)$term_ids[1]
            )
        );
        $result = $db->update('aci_link', $update, $where);

        if (!(bool)$result) {
            $ACI->json_response(array('error' => 'server_error'), 500);
        }
    }


    $data = array(
        'status' => 'success',
        'vote' => $post['vote'],
        'id' => $post['link_id']
    );

    $ACI->json_response($data, 200);
}


