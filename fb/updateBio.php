<?php

// this protocol is in charge of updating the Bio of the user and throwing back the user related information


// STEP 1. Check passed inf to this PHP file
if (empty($_REQUEST['id'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode($return);
    return;
}

$id = htmlentities($_REQUEST['id']);
$bio = htmlentities($_REQUEST['bio']);


// STEP 2. Establish connection
require('secure/access.php');
$access = new access('localhost', 'root', '', 'fb');
$access->connect();


// STEP 3. Update Bio
$result = $access->updateBio($id, $bio);

// updated successfully
if ($result) {
    
    // select user to throw back to the user updated inf
    $user = $access->selectUserID($id);

    // throw back updated user information    
    if ($user) {
        $return['status'] = '200';
        $return['message'] = 'Bio has been updated';
        $return['id'] = $user['id'];
        $return['email'] = $user['email'];
        $return['firstName'] = $user['firstName'];
        $return['lastName'] = $user['lastName'];
        $return['birthday'] = $user['birthday'];
        $return['gender'] = $user['gender'];
        $return['cover'] = $user['cover'];
        $return['ava'] = $user['ava'];
        $return['bio'] = $user['bio'];
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not complete the process';
    }
    
// error while updating
} else {
    $return['status'] = '400';
    $return['message'] = 'Unable to update bio';
}


echo json_encode($return);
$access->disconnect();
