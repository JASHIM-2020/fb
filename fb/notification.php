<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// This file is responsible for inserting, deleting, updating and selecting all notifications from the server related to the current user

// Checking did the file received all necessary information
if (!isset($_REQUEST['byUser_id']) || !isset($_REQUEST['user_id']) || !isset($_REQUEST['type'])) {
    $return['status'] = '400';
    $return['message'] = 'Missing required information';
    echo json_encode($return);
    return;
}

// casting received values and converting them to the variables
$byUser_id = htmlentities($_REQUEST['byUser_id']);
$user_id = htmlentities($_REQUEST['user_id']);
$type = htmlentities($_REQUEST['type']);



// Establish connection
require('secure/access.php');
$access = new access('localhost', 'root', '', 'fb');
$access->connect();



// Execute Insert Method
if ($_REQUEST['action'] == 'insert') {
    
    // run insert function
    $result = $access->insertNotification($byUser_id, $user_id, $type);
    
    // inserted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Notification is inserted';
        
    // failed
    } else {
        $return['status'] = '400';
        $return['message'] = 'Notification could not be inserted';
    }
    
// Execute Delete Method
} else if ($_REQUEST['action'] == 'delete') {
    
    // run delete function
    $result = $access->deleteNotification($byUser_id, $user_id, $type);
    
    // deleted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Notification is deleted';
        
    // failed
    } else {
        $return['status'] = '400';
        $return['message'] = 'Notification could not be deleted';
    }
    
// Execute Select Method
} else if ($_REQUEST['action'] == 'select') {
    
    // checking for receiving limit and offset values
    if (!isset($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        $access->disconnect();
        echo json_encode($return);
        return;
    }
    
    // safe mode of casting limit and access
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);
    
    // assign all selected notifications to $notifications var
    $notifications = $access->selectNotifications($user_id, $limit, $offset);
    
    // selected
    if ($notifications) {
        $return['notifications'] = $notifications;
        
    // could not select
    } else {
        $return['status'] = '400';
        $return['message'] = 'No notifications are selected';
    }
 
// Execute Update Method
} else if ($_REQUEST['action'] == 'update') {
    
    // checking for receiving id of the notification
    if (!isset($_REQUEST['id']) || !isset($_REQUEST['viewed'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        $access->disconnect();
        echo json_encode($return);
        return;
    }
    
    // safe mode of casting value under the key id received
    $id = htmlentities($_REQUEST['id']);
    $viewed = htmlentities($_REQUEST['viewed']);
    
    // exec-ing update function
    $result = $access->updateNotification($viewed, $id);
    
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Notification is successfully updated';
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not update the notification';
    }
    
}

// Return JSON and close the connection
echo json_encode($return);
$access->disconnect();