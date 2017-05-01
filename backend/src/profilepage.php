<?php
// Routes for the profile page

// Get takes in a username and returns a user's info as Json
$app->get('/users/{username}', function($request, $response, $args) {
    $pdo = $this->db;

    $username = $args['username'];

    // Ensure the $username field is populated
    if ( !$isset($username) || !$isempty($username) ){
	     return $response->withStatus(418);
    }

    // This sequence stores the user in $user
    $sql_get_user = $pdo->prepare(
    	'SELECT username, first_name, last_name, profile_pic, bg_pic, bio, birthday
          FROM users WHERE :username'
    );
    $sql_get_user->bindParam("username", $username);
    $sql_get_user->execute();
    $user = $sql_get_user->fetch();

    if($user) {
        return $response->withJson($user, 302);
    } else {
        return $response->withStatus(404);
    }
});
