<?php
//returns a json file according to supplied array
function buildJson($data, $builder){
    if(!is_array($data)){
        exit;
    }

    $json = $builder
        ->setValues(array('links' => $data))
        ->build();


    header("Content-Type: application/json; charset=utf-8");
    echo $json;
    exit;
}