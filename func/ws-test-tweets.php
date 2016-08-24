<?php

add_action("wp_ajax_nopriv_ws_test_tweets", "ws_rio_capibaribe_tweets");
add_action("wp_ajax_ws_test_tweets", "ws_test_tweets");


function ws_test_tweets(){
    fetch_tweets_every_fifteen_minutes();
    die();
}