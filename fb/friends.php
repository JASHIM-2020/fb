<?php

// responsible for selecting all users from the server or accepting friendship request or deleting the frind and so on


// STEP 1. Establish connection
require('secure/access.php');
$access = new access('localhost', 'root', '', 'fb');
$access->connect();



// STEP 2. Execute commands
// Search Users
if (($_REQUEST['action']) == 'search') {
    
    // check if the values have been passed or not to current PHP file
    if (!isset($_REQUEST['name']) || !isset($_REQUEST['id']) || !isset($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // secured way of casting values
    $name = htmlentities($_REQUEST['name']);
    $id = htmlentities($_REQUEST['id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);
    
    // run function to select all users
    $users = $access->selectUsers($name, $id, $limit, $offset);
    
    // if we got something
    if ($users) {
        
        $return['users'] = $users;
        
    // nothing has been gotten
    } else {
        
        $return['status'] = '400';
        $return['message'] = 'No users were found';
        
    }
    
// Request Friendship
} else if (($_REQUEST['action']) == 'add') {
    
    // checking are the required details passed to current file or not
    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // secured way of accessing received inf
    $user_id = htmlentities($_REQUEST['user_id']);
    $friend_id = htmlentities($_REQUEST['friend_id']);
    
    // execute insertRequest function and assign result to the new variable
    $result = $access->insertRequest($user_id, $friend_id);
    
    // request inserted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Request has been sent successfully';
        
    // error while inserting
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not send a request';
    }
    
    
// Reject Friendship Request
} else if (($_REQUEST['action']) == 'reject') {
    
    // checking are the required details passed to current file or not
    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // secured way of accessing received inf
    $user_id = htmlentities($_REQUEST['user_id']);
    $friend_id = htmlentities($_REQUEST['friend_id']);
    
    // execute insertRequest function and assign result to the new variable
    $result = $access->deleteRequest($user_id, $friend_id);
    
    // request deleted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Request has been rejected successfully';
        
    // error while deleting
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not reject a request';
    }
    
// Select All Friendship Requests
} else if (($_REQUEST['action']) == 'requests') {
    
    // check if the values have been passed or not to current PHP file
    if (!isset($_REQUEST['id']) || !isset($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // secure way of assigning received information to PHP vars
    $id = htmlentities($_REQUEST['id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);
    
    // run function to select requests
    $requests = $access->selectRequests($id, $limit, $offset);
    
    // requests selected successfully
    if ($requests) {
        $return['requests'] = $requests;
    // failed to select requests
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not select requests';
    }
    
// Confirm the Friend
} else if (($_REQUEST['action']) == 'confirm') {
 
    // checking are the required details passed to current file or not
    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // secured way of accessing received inf
    $user_id = htmlentities($_REQUEST['user_id']);
    $friend_id = htmlentities($_REQUEST['friend_id']);
    
    // assign the result of exec (insertFriend) to $result var
    $result = $access->insertFriend($user_id, $friend_id);
    
    // Confirmed
    if ($result) {
        
        // delete request from Requests Table after it got confirmed (it goes to friends)
        $access->deleteRequest($user_id, $friend_id);
        
        $return['status'] = '200';
        $return['message'] = 'Friend is confirmed successfully';
        
    // Failed to Confirm
    } else {
        $return['status'] = '400';
        $return['message'] = 'Failed to confirm the friend';
    }
    
// Deleting the Friend
} else if (($_REQUEST['action']) == 'delete') {
    
    // checking are the required details passed to current file or not
    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // safe mode of casting received inf
    $user_id = htmlentities($_REQUEST['user_id']);
    $friend_id = htmlentities($_REQUEST['friend_id']);
    
    // execute insertRequest function and assign result to the new variable
    $result = $access->deleteFriend($user_id, $friend_id);
    
    // request deleted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Friend has been deleted successfully';
        
    // error while deleting
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not delete a friend';
    }
    
   
// Follow User
} else if (($_REQUEST['action']) == 'follow') {
    
    // checking are the required details passed to current file or not
    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // secured way of accessing received inf
    $user_id = htmlentities($_REQUEST['user_id']);
    $follow_id = htmlentities($_REQUEST['friend_id']);
    
    // execute insertRequest function and assign result to the new variable
    $result = $access->insertFollow($user_id, $follow_id);
    
    // request inserted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Started following successfully';
        
    // error while inserting
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not follow the user';
    }
    
// Unfollow User
} else if (($_REQUEST['action']) == 'unfollow') {
    
    // checking are the required details passed to current file or not
    if (!isset($_REQUEST['user_id']) || !isset($_REQUEST['friend_id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // safe mode of casting received inf
    $user_id = htmlentities($_REQUEST['user_id']);
    $follow_id = htmlentities($_REQUEST['friend_id']);
    
    // execute insertRequest function and assign result to the new variable
    $result = $access->deleteFollow($user_id, $follow_id);
    
    // request deleted
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Stopped following successfully';
        
    // error while deleting
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not stop following the user';
    }
    
// Scenario for selecting all Recommended Users
} else if (($_REQUEST['action']) == 'recommended') {
    
    // check if the values have been passed or not to current PHP file
    if (!isset($_REQUEST['id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // secure way of assigning received information to PHP vars
    $id = htmlentities($_REQUEST['id']);
    
    // run function to select requests
    $users = $access->selectRecommendedFriends($id);
    
    // requests selected successfully
    if ($users) {
        $return['users'] = $users;
    // failed to select requests
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not select requests';
    }
    
// Scenario to select all friends of the user (via id)
} else if ($_REQUEST['action'] == 'friends') {
    
    // checking if required information is received to exec further function or not
    if (!isset($_REQUEST['id']) || !isset($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information';
        echo json_encode($return);
        return;
    }
    
    // safe mode of casting received information
    $id = htmlentities($_REQUEST['id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);
    
    
    // exec-ing function and assigning all results to $friends var
    $friends = $access->selectFriends($id, $limit, $offset);
    
    // friends selected successfully
    if ($friends) {
        $return['friends'] = $friends;
        
    // could not select friends - no friends
    } else {
        $return['status'] = '400';
        $return['message'] = 'No friends have been found';
    }
    
}



echo json_encode($return);
$access->disconnect();
