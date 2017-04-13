<?php
// Routes for the profile page

// Get takes in a username and returns a user's info as Json
$app->get('/users/id', function($username, $response) {  
    // Ensure the $username field is populated
    if (!$isset($username) {
	return $response->withStatus(418);    
    }
        
    // This sequence stores the user in $user
    $sqlGetUser = $pdo->prepare(
    	'SELECT username, first_name, last_name, profile_pic, bg_pic, bio, birthday
          FROM users WHERE username = ?'
    );
    $sqlGetUser->execute([$username]);
    $user = $sqlGetUser->fetch();
	
    return $this->response->withJson($user, 302);
    }
);

$app->delete('/users/id', function($username, $response) {  
	if (!$username->isEmpty()) {
		// This counts how many users there are with that username
		// to verify that the user actually exists
		if (!$isset($username) {
			return $response->withStatus(418);    
		}
		
		$sqlGetUser = $pdo->prepare(
			'DELETE FROM users WHERE username = ?'
		);
		$sqlGetUser->execute([$username]);
		
		/*
		$sqlVerifyUser->execute([$username]);
		$userCount = $sqlVerifyUser->fetch();
		if ($userCount == 0){
			echo "The user is deleted.";
		}else {
			echo "The deleting function failed.";
		}
		*/
	}
}); 
