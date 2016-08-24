<?php


header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");


add_action("wp_ajax_nopriv_ws_rio_capibaribe_tweets", "ws_rio_capibaribe_tweets");
add_action("wp_ajax_ws_rio_capibaribe_tweets", "ws_rio_capibaribe_tweets");


function ws_rio_capibaribe_tweets(){

    $teste = bdq_trs_query(100);

    echo json_encode($teste);
    die();
}
