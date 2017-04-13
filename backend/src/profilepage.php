<?php
// Routes for the profile page

// Get takes in a username and returns a user's info as Json
$app->get('/users/id', function($username = null) {  
    // Ensure the $username field is full
    if (!$username->isEmpty()) {
        // This counts how many users there are with that username
        // to verify that the user actually exists
        $sqlVerifyUser = $pdo->prepare(
            'SELECT COUNT(*) FROM users WHERE username = ?'
        );
        $sqlVerifyUser->execute([$username]);
        $userCount = $sqlVerifyUser->fetch();
        
        if ($userCount == 1) {
            // This sequence stores the user in $user
            $sqlGetUser = $pdo->prepare(
                'SELECT username, first_name, last_name, profile_pic, bg_pic, bio, birthday
                FROM users WHERE username = ?'
            );
            $sqlGetUser->execute([$username]);
            $user = $sqlGetUser->fetch();
           return $this->response->withJson($user);
        }
    }
  }
);
