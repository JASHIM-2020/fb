<?php

// this file is responsible for inserting / deleting / showing comments to the users


// STEP 1. Establish connection
require('secure/access.php');
$access = new access('localhost', 'root', '', 'fb');
$access->connect();


// declare array to store all information for throwing back to the user
$return = array();


// STEP 2. Insert / Delete / Show comments
if ($_REQUEST['action'] == 'insert') {
    
    if (empty($_REQUEST['post_id']) || empty($_REQUEST['user_id']) || empty($_REQUEST['comment'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information for insert';
        echo json_encode($return);
        $access->disconnect();
        return;
    }

    // safe mode of casting received values
    $post_id = htmlentities($_REQUEST['post_id']);
    $user_id = htmlentities($_REQUEST['user_id']);
    $comment = htmlentities($_REQUEST['comment']);
    
    
    // run function to insert the comment and assign the result of exec-d function to $result var
    $result = $access->insertComment($post_id, $user_id, $comment);
    
    // if the $result is positivie (it means inserted the comment successfully), else -> failed
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Commented successfully';
        $return['new_comment_id'] = mysqli_insert_id($access->conn);
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not comment';
    }
    
// selecting the comments
} else if ($_REQUEST['action'] == 'select') {
    
    // checking for the existance of id or limit or offset information
    if (empty($_REQUEST['post_id']) || empty($_REQUEST['limit']) || !isset($_REQUEST['offset'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information for select';
        echo json_encode($return);
        $access->disconnect();
        return;
    }
    
    // safe mode of casting received data
    $post_id = htmlentities($_REQUEST['post_id']);
    $limit = htmlentities($_REQUEST['limit']);
    $offset = htmlentities($_REQUEST['offset']);
    
    // exec func that selects all comments of the post and assing all results to $comments
    $comments = $access->selectComments($post_id, $offset, $limit);
    
    // selected successfully
    if ($comments) {
        $return['comments'] = $comments;
    }

// delete the comment
} else if ($_REQUEST['action'] == 'delete') {
    
    if (empty($_REQUEST['id'])) {
        $return['status'] = '400';
        $return['message'] = 'Missing required information for delete';
        echo json_encode($return);
        $access->disconnect();
        return;
    }
    
    // safe mode of casting comment's id
    $id = htmlentities($_REQUEST['id']);
    
    // run protocol to delete the comment and assign the result of exec-d protocol to $result var
    $result = $access->deleteComment($id);
    
    // Deleted successfully
    if ($result) {
        $return['status'] = '200';
        $return['message'] = 'Comment has been deleted successfully';
        
    // Could not delete Comment
    } else {
        $return['status'] = '400';
        $return['message'] = 'Could not delete comment';
    }
    
}


// STEP 4. Stop connection
$access->disconnect();
echo json_encode($return);