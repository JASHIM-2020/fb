<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require('secure/access.php');
$access = new access('localhost', 'root', '', 'fb');
$access->connect();

$id = htmlentities($_REQUEST['id']);
    
$users = $access->selectRecommendedFriends($id);


echo json_encode($users);
$access->disconnect();
