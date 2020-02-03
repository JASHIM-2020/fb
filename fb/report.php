<?php

// Reponsible for inserting reports and complaints into database


// STEP 1. check if the requried information passed to current PHP file or not
if (!isset($_REQUEST['byUser_id']) || !isset($_REQUEST['user_id']) || !isset($_REQUEST['post_id']) || !isset($_REQUEST['reason'])) {    
    $return['status'] = '200';
    $return['message'] = 'Missing required information';
    echo json_encode($return);
    return;
}

// safe mode of casting value
$post_id = htmlentities($_REQUEST['post_id']);
$user_id = htmlentities($_REQUEST['user_id']);
$reason = htmlentities($_REQUEST['reason']);
$byUser_id = htmlentities($_REQUEST['byUser_id']);



// STEP 2. Build connection with the database
require('secure/access.php');
$access = new access('localhost', 'root', '', 'fb');
$access->connect();



// STEP 3. Insert Complaint
$result = $access->insertReport($post_id, $user_id, $reason, $byUser_id);

// check the status of exec-tion
// inserted successfully
if ($result) {
    $return['status'] = '200';
    $return['message'] = 'Reported successfully';

    
// could not insert
} else {
    $return['status'] = '400';
    $return['message'] = 'Error while sending your feedback';
}


// STEP 4. Disconnect and provide user with the JSON information
$access->disconnect();
echo json_encode($return);