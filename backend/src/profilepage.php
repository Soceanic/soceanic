<?php
// Routes for the profile page

// Get takes in a username and returns a user's info as Json
$app->get('/users/id', function($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

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


// // This function is part of the settings page and needs to be moved
// $app->delete('/users/id', function($request, $response, $args) {
// 	if (!$username->isEmpty()) {
//
// 		// This counts how many users there are with that username
// 		// to verify that the user actually exists
// 		if (!$isset($username) {
// 			return $response->withStatus(418);
// 		}
//
// 		$sql_delete_user = $pdo->prepare(
// 			'DELETE FROM users WHERE :username'
// 		);
//
//     $sql_delete_user->bindParam("username", $username);
// 		$sql_delete_user->execute();
//
// 		/*
// 		$sqlVerifyUser->execute([$username]);
// 		$userCount = $sqlVerifyUser->fetch();
// 		if ($userCount == 0){
// 			echo "The user is deleted.";
// 		}else {
// 			echo "The deleting function failed.";
// 		}
// 		*/
// 	}
// });
