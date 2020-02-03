<?php


//This coding class is in charge of the communication with the server


class access {
    
    // variables for construction of the class and access to the server
    var $host = null;
    var $user = null;
    var $pass = null;
    var $name = null;
    
    var $conn = null;
    var $result = null;
    
    
    // build the full class
    function __construct($host, $user, $pass, $name) {
        
        // caching the server access data in order to use them later
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->name = $name;
        
        // establish connection
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        
        // error occured while constructing the class
        if (mysqli_connect_errno()) {
            echo 'Could not construct';
            return;
        }
        
        $this->conn->set_charset('utf8');
        
    }
    
    
    // establish connection with the server
    public function connect() {
        
        // establishing connection
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        
        // error occured while connecting
        if (mysqli_connect_errno()) {
            echo 'Could not connect';
            return;
        }
        
        $this->conn->set_charset('utf8');
        
    }
    
    
    // disconnect from the server once we finished using the server connection
    public function disconnect() {
        
        // if the connection is not null (established), close it
        if ($this->conn != null) {
            $this->conn->close();
        }
        
    }
    
    
    // Will try to select any value in database based on received Email
    public function selectUser($email) {
        
        // array to store full user related information with the logic: Key=>Value (Name=>John)
        $returnArray = array();
        
        // SQL Language / Commande to be sent to the server
        // SELECT * FROM users WHERE email='john@yahoo.com'
        $sql = "SELECT * FROM users WHERE email='" . $email . "'";
        
        // execuring query via already established connection with the server
        $result = $this->conn->query($sql);
        
        // result isn't zero and it has at least 1 row / value / result
        if ($result != null && (mysqli_num_rows($result)) >= 1) {
            
            // converting to be a JSON type
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            // assign fetched row to ReturnArray
            if (!empty($row)) {
                $returnArray = $row;
            }
            
        }
        
        // throw back returnArray
        return $returnArray;
        
    }
    
    
    // Inserting Data in the server receiving from the user (e.g. register.php)
    public function insertUser($email, $firstName, $lastName, $encryptedPassword, $salt, $birthday, $gender) {
        
        // SQL Language - command to inser data
        $sql = "INSERT INTO users SET email=?, firstName=?, lastName=?, password=?, salt=?, birthday=?, gender=?, allow_friends=1, allow_follow=1";
        
        // preparing SQL for execution by checking the validity
        $statement = $this->conn->prepare($sql);
        
        // if error
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // assigning a variables instead of '?', after checking the preparation and validty of the SQL command
        $statement->bind_param('sssssss', $email, $firstName, $lastName, $encryptedPassword, $salt, $birthday, $gender);
        
        // $result will store the status / result of the execution of SQL command
        $result = $statement->execute();
        
        return $result;
        
    }
      
    
    // updating the path of the image (stored in the server) in the database
    function updateImageURL($type, $path, $id) {
        
        // UPDATE users SET ava=? WHERE id=?
        $sql = 'UPDATE users SET ' . $type . '=? WHERE id=?';
        
        // prepare command to be executed
        $statement = $this->conn->prepare($sql);
        
        // if error occured while execution
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // assigning parameters to the prepared command execution
        $statement->bind_param('si', $path, $id);
        
        // $result will store the result of executed statement
        $result = $statement->execute();
                
        return $result;
        
    }
    
    
      // Will try to select any value in database based on received Email
    public function selectUserID($id) {
        
        // array to store full user related information with the logic: Key=>Value (Name=>John)
        $returnArray = array();
        
        // SQL Language / Commande to be sent to the server
        // SELECT * FROM users WHERE id='777'
        $sql = "SELECT * FROM users WHERE id='" . $id . "'";
        
        // execuring query via already established connection with the server
        $result = $this->conn->query($sql);
        
        // result isn't zero and it has at least 1 row / value / result
        if ($result != null && (mysqli_num_rows($result)) >= 1) {
            
            // converting to be a JSON type
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            // assign fetched row to ReturnArray
            if (!empty($row)) {
                $returnArray = $row;
            }
            
        }
        
        // throw back returnArray
        return $returnArray;
        
    }
    
    
    // updates bio in the server
    function updateBio($id, $bio) {
        
        // declaring SQL Command
        $sql = 'UPDATE users SET bio=? WHERE id=?';
        
        // prepare SQL Command to be exec
        $statement = $this->conn->prepare($sql);
        
        // if error occured while preparing the statement to be exec
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // assign params to the prepapred SQL Commnad
        $statement->bind_param('si', $bio, $id);
        
        // access result of exec
        $result = $statement->execute();
                
        // returning result of exec
        return $result;
        
    }
    
    
    // inserting post into table/database
    function insertPost($user_id, $text, $picture) {
        
        // sql statement to be ran
        $sql = 'INSERT INTO posts SET user_id=?, text=?, picture=?';
        
        // preparing SQL command for the execution
        $statement = $this->conn->prepare($sql);
        
        // show error if statement couldn't be executed
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replacing ? with the variables
        $statement->bind_param('iss', $user_id, $text, $picture);
        
        // execute statement and keep the result in $result variable
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // responsible of update user related information
    public function updateUser($email, $firstName, $lastName, $birthday, $gender, $allow_friends, $allow_follow, $id) {
        
        // sql command to be sent to the server for execution
        $sql = "UPDATE users SET email=?, firstName=?, lastName=?, birthday=?, gender=?, allow_friends=?, allow_follow=? WHERE id=?";
        
        // preparing sql command
        $statement = $this->conn->prepare($sql);
        
        // checking sql command
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // assigning values/replacing with vars
        $statement->bind_param("sssssiii", $email, $firstName, $lastName, $birthday, $gender, $allow_friends, $allow_follow, $id);
        
        // execute sql command
        $result = $statement->execute();
        
        // return the result of final execution
        return $result;
        
    }
    
    
    // responsible for updating the password of the user
    public function updatePassword($id, $password, $salt) {
        
        // sql command to be sent to the server for execution
        $sql = "UPDATE users SET password=?, salt=? WHERE id=?";
        
        // preparing sql command
        $statement = $this->conn->prepare($sql);
        
        // checking sql command
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // assigning values/replacing with vars
        $statement->bind_param("ssi", $password, $salt, $id);
        
        // execute sql command
        $result = $statement->execute();
        
        // return the result of final execution
        return $result;
        
    }
    
    
    public function selectPosts($id, $offset, $limit) {
        
        // array to store the information / posts
        $return = array();
        
        // sql command to be exec-d
        $sql = "SELECT 
		posts.id, 
                posts.user_id, 
                posts.text, 
                posts.picture, 
                posts.date_created,
                users.firstName,
                users.lastName,
                users.cover,
                users.ava,
                likes.post_id AS liked

                FROM posts
                
                LEFT JOIN users ON users.id = posts.user_id
                LEFT JOIN likes ON posts.id = likes.post_id

                WHERE posts.user_id = $id
                    
                ORDER BY posts.date_created DESC LIMIT $limit OFFSET $offset";
        
        // preparing sql command to be exec-d and then we store the result of preparation in statement var
        $statement = $this->conn->prepare($sql);
                
        // show error occured while preparing the sql command for exec-tion
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // execute sql command
        $statement->execute();
        
        // retrieve results from the query / sql
        $result = $statement->get_result();
        
        // all rows (posts) are stored in $result. we are fetching every row one by one. And assigning it to $return var
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
        
        return $return;
        
    }
    
    
    // inserting like-information into the database
    function insertLike($post_id, $user_id) {
        
        // sql command to be sent to db
        $sql = 'INSERT INTO likes SET post_id=?, user_id=?';
        
        // prepare sql command for exec-tion
        $statement = $this->conn->prepare($sql);
        
        // checking is statement having any errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replace ? with the valid params / values
        $statement->bind_param('ii', $post_id, $user_id);
        
        // execute the statement and store the result of exec-tion in the new var
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // deleting like-information into the database
    function deleteLike($post_id, $user_id) {
        
        // sql command to be sent to db
        $sql = 'DELETE FROM likes WHERE post_id=? AND user_id=?';
        
        // prepare sql command for exec-tion
        $statement = $this->conn->prepare($sql);
        
        // checking is statement having any errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replace ? with the valid params / values
        $statement->bind_param('ii', $post_id, $user_id);
        
        // execute the statement and store the result of exec-tion in the new var
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // insert comment into db
    function insertComment($post_id, $user_id, $comment) {
        
        // sql command to be executed
        $sql = 'INSERT INTO comments SET post_id=?, user_id=?, comment=?';
        
        // preparing sql command for exec-tion
        $statement = $this->conn->prepare($sql);
        
        // error happened
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // assigning params to the exec-ion
        $statement->bind_param('iis', $post_id, $user_id, $comment);
        
        // execute the command
        $result = $statement->execute();
        
        // return back to the user the result we got
        return $result;
        
    }
    
    
    // select all comments related to the certain post
    function selectComments($post_id, $offset, $limit) {
        
        $return = array();
        
        // sql command to be exec-d
        $sql = "SELECT
		
                comments.id,
                comments.post_id,
                comments.user_id,
                comments.comment,
                comments.date_created,
                posts.text,
                posts.picture,
                users.firstName,
                users.lastName,
                users.ava
        
                FROM comments
        
                LEFT JOIN posts ON posts.id = comments.post_id
                LEFT JOIN users ON users.id = comments.user_id
        
                WHERE posts.id = $post_id
        
                ORDER BY comments.date_created ASC LIMIT $limit OFFSET $offset";
        
        // prepare the sql command for exec
        $statement = $this->conn->prepare($sql);
        
        // error while preparing for exec
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // exec the command
        $statement->execute();
        
        // retrieving all results from the exec-tion and assigning it to $result
        $result = $statement->get_result();
        
        // looping through every single result / row and assigning it to the return array
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
        
        return $return;
        
    }
    
    
    // deletes certain comment based on the comment's id
    function deleteComment($id) {
        
        // sql command
        $sql = 'DELETE FROM comments WHERE id=?';
        
        // prepare sql command for the exec
        $statement = $this->conn->prepare($sql);
        
        // check for errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replace ? with the param
        $statement->bind_param('i', $id);
        
        // run execution
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // deleting the post and all post related inforamtion: comments and likes
    function deletePost($id) {
        
        // full sql command
        $sql = "DELETE FROM posts WHERE id='" . $id . "'; "; // DELETE FROM posts WHERE id='1'
        $sql .= "DELETE FROM likes WHERE post_id='" . $id . "'; "; // DELETE FROM posts WHERE id='1'; DELETE FROM likes WHERE id='1'
        $sql .= "DELETE FROM comments WHERE post_id='" . $id . "'; "; // DELETE FROM posts WHERE id='1'; DELETE FROM likes WHERE id='1'; DELETE FROM comments WHERE post_id='1';
        
        // execute several sql commands
        $this->conn->multi_query($sql);
        
        // getting affected rows from the just executed query using 'connection'-> $conn
        $result = mysqli_affected_rows($this->conn);
        
        return $result;
    }
    
    
    // select all users as per searching name, except current user
    function selectUsers($name, $id, $limit, $offset) {
        
        // create array to store ALL users fetched
        $return = array();
        
        /*
        // sql command to be executed
        $sql = "SELECT users.id,
		
                users.email,
                users.firstName,
                users.lastName,
                users.birthday,
                users.gender,
                users.cover,
                users.ava,
                users.bio,
                users.date_created
        
                FROM users
        
                WHERE users.id != $id AND users.firstName LIKE '%$name%' OR users.lastName LIKE '%$name%' AND users.id != $id
                LIMIT $limit OFFSET $offset";
        */
       
        /*
        // UPDATED VERSION AS PER FRIENDSHIP REQUEST
        $sql = "
                SELECT
                
                users.id,
                users.email,
                users.firstName,
                users.lastName,
                users.birthday,
                users.gender,
                users.ava,
                users.cover,
                users.bio,
                users.date_created,
                requests.id AS requested

                FROM users

                LEFT JOIN requests ON users.id = requests.friend_id

                WHERE users.id != $id AND users.firstName LIKE '%$name%' OR users.lastName LIKE '%$name%' AND users.id != $id
                
                LIMIT $limit OFFSET $offset

                ";
        */
        
        $sql = "SELECT DISTINCT
                
                users.id,
                users.email,
                users.firstName,
                users.lastName,
                users.birthday,
                users.gender,
                users.ava,
                users.cover,
                users.bio,
                users.allow_friends,
                users.allow_follow,
                users.date_created,
                requests.user_id AS request_sender,
                requests.friend_id AS request_receiver,
                friends.user_id AS friendship_sender,
                friends.friend_id AS friendship_receiver,
                follows.follow_id AS followed_user

                FROM users

                LEFT JOIN requests ON users.id = requests.user_id AND requests.friend_id = $id OR users.id = requests.friend_id AND requests.user_id = $id
                LEFT JOIN friends ON users.id = friends.user_id AND friends.friend_id = $id OR users.id = friends.friend_id AND friends.user_id = $id
                LEFT JOIN follows ON users.id = follows.follow_id AND follows.user_id = $id OR users.id = follows.user_id AND follows.follow_id = $id

                WHERE users.id != $id AND users.firstName LIKE '%$name%' OR users.lastName LIKE '%$name%' AND users.id != $id
                
                LIMIT $limit OFFSET $offset";
                
        
        // preparing sql command for exec-n
        $statement = $this->conn->prepare($sql);
        
        // throws back all error messages
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // execute sql command
        $statement->execute();
        
        // assign all results of execution to the $result variable
        $result = $statement->get_result();
        
        // get every single row of the fetched data and assign it to $row and further append every $row to $return array
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
        
        return $return;
                
    }
    
    
    // inserts friendship request into database
    function insertRequest($user_id, $friend_id) {
        
        // sql command for exec-tion
        $sql = 'INSERT INTO requests SET user_id=?, friend_id=?';
        
        // prepare sql command to be exec-d
        $statement = $this->conn->prepare($sql);
        
        // check for errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // assign vars
        $statement->bind_param('ii', $user_id, $friend_id);
        
        // execute
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // deletes inf from the server - requests table
    function deleteRequest($user_id, $friend_id) {
        
        // sql command for exec-tion
        $sql = 'DELETE FROM requests WHERE user_id=? AND friend_id=?';
        
        // prepare sql command to be exec-d
        $statement = $this->conn->prepare($sql);
        
        // check for errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // assign vars
        $statement->bind_param('ii', $user_id, $friend_id);
        
        // execute
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // selects all requests of the user ( : -> $id)
    function selectRequests($id, $limit, $offset) {
        
        // declaring array to store all info of users who sent requests
        $return = array();
        
        // sql command to select all users who sent requests
        $sql = "SELECT
            
		users.id,
                users.email,
                users.firstName,
                users.lastName,
                users.birthday,
                users.gender,
                users.ava,
                users.cover,
                users.bio,
                users.allow_friends,
                users.allow_follow,
                users.date_created,
                follows.follow_id AS followed_user

                FROM requests

                LEFT JOIN users ON requests.user_id = users.id
                LEFT JOIN follows ON requests.user_id = follows.follow_id

                WHERE requests.friend_id = $id
                
                LIMIT $limit OFFSET $offset

                ";
        
       // prepare sql for execution
       $statement = $this->conn->prepare($sql);
        
       // check if there are any errors
       if (!$statement) {
           throw new Exception($statement->error);
       }
       
       // execute sql command
       $statement->execute();
       
       // getting all results of sql command and assigning them to $result var
       $result = $statement->get_result();
               
       // fetching every row of entire result we got
       while ($row = $result->fetch_assoc()) {
           $return[] = $row;
       }
       
       return $return;
        
    }
    
    
    // inserts friend into friends table of FB database
    function insertFriend($user_id, $friend_id) {
        
        // sql command for execution
        $sql = 'INSERT INTO friends SET user_id=?, friend_id=?';
        
        // preparing sql for exec-tion
        $statement = $this->conn->prepare($sql);
        
        // error happened
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // appoint params instead of question marks
        $statement->bind_param('ii', $user_id, $friend_id);
        
        // assign result of exec-ion to $result var
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // deletes record in the Friends table
    function deleteFriend($user_id, $friend_id) {
        
        // sql command for exec
        $sql = 'DELETE FROM friends WHERE user_id=? AND friend_id=?';
        
        // preparing sql for exec
        $statement = $this->conn->prepare($sql);
        
        // checking for the errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // appointing vars instead of ??
        $statement->bind_param('ii', $user_id, $friend_id);
        
        // executing
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // inserts data into Follow db
    function insertFollow($user_id, $follow_id) {
        
        // sql command for exec
        $sql = 'INSERT INTO follows SET user_id=?, follow_id=?';
        
        // prepare for exec
        $statement = $this->conn->prepare($sql);
        
        // error while checking
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replace ? with var
        $statement->bind_param('ii', $user_id, $follow_id);
        
        // exec-ing
        $result = $statement->execute();
                  
        return $result;
              
    }
    
    
    // delete row from the db
    function deleteFollow($user_id, $follow_id) {
        
        // sql command for exec
        $sql = 'DELETE FROM follows WHERE user_id=? AND follow_id=?';
        
        // prepare for exec
        $statement = $this->conn->prepare($sql);
        
        // error while checking
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replace ? with var
        $statement->bind_param('ii', $user_id, $follow_id);
        
        // exec-ing
        $result = $statement->execute();
                  
        return $result;
        
    }
    
    
    // inserts details about the complaint
    function insertReport($post_id, $user_id, $reason, $byUser_id) {
        
        // sql command for exec
        $sql = 'INSERT INTO reports SET post_id=?, user_id=?, reason=?, byUser_id=?';
        
        // preparing sql for exec
        $statement = $this->conn->prepare($sql);
        
        // checking errors before exec
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replacing ? with vars
        $statement->bind_param('iisi', $post_id, $user_id, $reason, $byUser_id);
        
        // exec
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // will select friends of our friends which are recommended for the friendship
    public function selectRecommendedFriends($id) {
        
        // sql command
        $sql = "SELECT DISTINCT
		users.id,
                users.firstName,
                users.lastName,
                users.email,
                users.ava,
                users.cover,
                users.birthday,
                users.gender,
                users.bio,
		users.allow_friends,
		users.allow_follow,
                requests.user_id AS request_sender,
                requests.friend_id AS request_receiver



		FROM friends
		
                JOIN users ON friends.user_id = users.id AND friends.friend_id IN 

                (SELECT users.id FROM friends LEFT JOIN users ON (users.id = friends.user_id AND friends.friend_id = $id OR users.id = friends.friend_id AND friends.user_id = $id) WHERE friends.friend_id = $id OR friends.user_id = $id)

                OR friends.friend_id = users.id AND friends.user_id IN

                (SELECT users.id FROM friends LEFT JOIN users ON (users.id = friends.user_id AND friends.friend_id = $id OR users.id = friends.friend_id AND friends.user_id = $id) WHERE friends.friend_id = $id OR friends.user_id = $id)

                LEFT JOIN requests ON users.id = requests.user_id AND requests.friend_id = $id OR users.id = requests.friend_id AND requests.user_id = $id

                WHERE friends.user_id != $id
                AND friends.friend_id != $id
                AND users.allow_friends = 1
                AND requests.user_id IS NULL
                
                LIMIT 20
                
                ";
        
        // preparing sql for exec
        $statement = $this->conn->prepare($sql);
        
        // if errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // exec-ing
        $statement->execute();
        
        // appending all found users/results to $result
        $result = $statement->get_result();
        
        // new array to store all the users
        $users = array();
        
        // every row gets appended to the $users
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
        
    }    
    
    
    // inserts records into database
    function insertNotification($byUser_id, $user_id, $type) {
        
        // sql command for exec
        $sql = 'INSERT INTO notifications SET byUser_id=?, user_id=?, type=?';
        
        // preparing for exec
        $statement = $this->conn->prepare($sql);
        
        // checking for erros
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replacing ? with var
        $statement->bind_param('iis', $byUser_id, $user_id, $type);
        
        // executing command
        $result = $statement->execute();
        
        // returning the result
        return $result;
        
    }
    
    
    // delete records from the database
    function deleteNotification($byUser_id, $user_id, $type) {
        
        // sql command for exec
        $sql = 'DELETE FROM notifications WHERE byUser_id=? AND user_id=? AND type=?';
        
        // preparing for exec
        $statement = $this->conn->prepare($sql);
        
        // checking for erros
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replacing ? with var
        $statement->bind_param('iis', $byUser_id, $user_id, $type);
        
        // executing command
        $result = $statement->execute();
        
        // returning the result
        return $result;
        
    }
    
    
    // selects all notifications of current user
    function selectNotifications($id, $limit, $offset) {
        
        // sql command to be exec (receives notifications only from the friends)
        /*$sql = "
                SELECT 
                notifications.byUser_id,
		notifications.user_id,
                notifications.type,
                notifications.viewed,
                notifications.date_created,
                users.firstName,
                users.lastName,
                users.email,
                users.ava,
                users.cover,
                users.birthday,
                users.gender,
                users.bio

                FROM notifications

                JOIN users ON users.id = notifications.byUser_id

                WHERE notifications.user_id = $id AND notifications.byUser_id IN

                (SELECT users.id FROM friends LEFT JOIN users ON (users.id = friends.user_id AND friends.friend_id = $id
                OR users.id = friends.friend_id AND friends.user_id = $id) WHERE friends.friend_id = $id OR friends.user_id = $id)

                OR notifications.user_id = $id AND notifications.byUser_id = $id
                
                ORDER BY notifications.date_created DESC
                
                LIMIT $limit OFFSET $offset
                    
                ";
        */
        
        // sql command to be exec (receives ALLLLL notifications where currentUser was targeted by)
        $sql = "
                SELECT 
                notifications.id,
                notifications.byUser_id,
		notifications.user_id,
                notifications.type,
                notifications.viewed,
                notifications.date_created,
                users.firstName,
                users.lastName,
                users.email,
                users.ava,
                users.cover,
                users.birthday,
                users.gender,
                users.bio

                FROM notifications

                JOIN users ON users.id = notifications.byUser_id

                WHERE notifications.user_id = $id AND notifications.viewed != 'ignore'
                
                ORDER BY notifications.date_created DESC
                
                LIMIT $limit OFFSET $offset
                    
                ";
        
        // preparing sql for exec
        $statement = $this->conn->prepare($sql);
        
        // checking for errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // exec-ing
        $statement->execute();
        
        // getting all results
        $result = $statement->get_result();
                
        // new array to store notifications
        $notifications = array();
        
        // appending each row 1 by 1 to $notifications var
         while ($row = $result->fetch_assoc()) {
             $notifications[] = $row;
        }
        
        // returning array
        return $notifications;
        
    }
    
    
    // updates notification is it viewed or "never show"
    function updateNotification($viewed, $id) {
        
        // sql command for exec
        $sql = "UPDATE notifications SET viewed=? WHERE id=?";
        
        // preparing sql for exec
        $statement = $this->conn->prepare($sql);
        
        // checking for errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // replacing ? with vars
        $statement->bind_param('si', $viewed, $id);
        
        // executing
        $result = $statement->execute();
        
        return $result;
        
    }
    
    
    // selects all friends of the current user
    function selectFriends($id, $limit, $offset) {
        
        // sql command for exec
        $sql = "
                SELECT DISTINCT
                friends.user_id,
                friends.friend_id,
                friends.date_created,
                users.id,
                users.email,
                users.firstName,
                users.lastName,
                users.ava,
                users.cover,
                users.birthday,
                users.gender,
                users.bio,
                users.allow_friends,
                users.allow_follow

                FROM friends

                LEFT JOIN users ON friends.user_id = users.id AND friends.user_id != $id OR friends.friend_id = users.id AND friends.friend_id != $id

                WHERE friends.user_id = $id OR friends.friend_id = $id

                ORDER BY friends.date_created DESC

                LIMIT $limit OFFSET $offset
                ";
        
        //preparing sql for exec
        $statement = $this->conn->prepare($sql);
        
        // checking for having an error
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // exec-ing
        $statement->execute();
        
        // assigning all results of exec-tion to $result var
        $result = $statement->get_result();
        
        // new array to store all friends (by row)
        $friends = array();
        
        // getting each row and assigning appending to $friends var
        while ($row = $result->fetch_assoc()) {
            $friends[] = $row;
        }
        
        return $friends;
        
    }
    
    
    // selects all the posts for the feed: friends, followed users and current users
    function selectPostsForFeed($id, $offset, $limit) {
        
        // sql command for the exec
        $sql = "
                SELECT 
		posts.id,
                posts.user_id,
                posts.text,
                posts.picture,
                posts.date_created,
                users.email,
                users.firstName,
                users.lastName,
                users.ava,
                users.cover,
                likes.post_id AS liked

                FROM posts

                LEFT JOIN users ON posts.user_id = users.id
                LEFT JOIN follows ON posts.user_id = follows.user_id OR posts.user_id = follows.follow_id
                LEFT JOIN likes ON posts.id = likes.post_id
        
		WHERE posts.user_id IN
        
                (SELECT users.id FROM friends LEFT JOIN users ON (users.id = friends.user_id AND friends.friend_id = $id 
                 OR users.id = friends.friend_id AND friends.user_id = $id) WHERE friends.user_id = $id OR friends.friend_id = $id)

                OR posts.user_id IN 

                (SELECT users.id FROM follows LEFT JOIN users ON (users.id = follows.user_id AND follows.follow_id = $id 
                 OR users.id = follows.follow_id AND follows.user_id = $id) WHERE follows.user_id = $id OR follows.follow_id = $id)

                OR posts.user_id = $id

                ORDER BY posts.date_created DESC

                LIMIT $limit OFFSET $offset
                ";
        
        // prepare the command for the exec
        $statement = $this->conn->prepare($sql);
        
        // check for errors
        if (!$statement) {
            throw new Exception($statement->error);
        }
        
        // exec the command
        $statement->execute();
        
        // get all results of the exec-tion
        $result = $statement->get_result();
        
        // array var to store json users
        $return = array();
        
        // access every row of the exec and assign it to the new variable
        while ($row = $result->fetch_assoc()) {
            $return[] = $row;
        }
        
        return $return;
        
    }
    
    
}