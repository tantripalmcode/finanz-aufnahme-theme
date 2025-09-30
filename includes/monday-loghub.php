<?php

add_action('create_monday_item_success', function($response, $id){
    LogHub::log('monday_create_item', $response, LogHub::TYPE_SUCCESS,'bundesweit.digital');
}, 10, 2);

add_action('create_monday_item_error', function($response){
    LogHub::log('monday_create_item', $response, LogHub::TYPE_ERROR,'bundesweit.digital');
});
