<?php
// Routes for the profile page

// Get takes in a username and returns a user's info as Json
$app->get('/users/{username}', function($request, $response, $args) {
    $pdo = $this->db;

    $username = $args['username'];

    // Ensure the $username field is populated
    if ( !isset($username) || !empty($username) ){
	     return $response->withStatus(418);
    }

    // This sequence stores the user in $user
    $sql_get_user = $pdo->prepare(
    	'SELECT username, first_name, last_name, profile_pic, bg_pic, bio, birthday
          FROM users WHERE username=:username'
    );
    $sql_get_user->bindParam("username", $username);
    $sql_get_user->execute();
    $user = $sql_get_user->fetch();

    if($user) {
      $stmt = $pdo->prepare('SELECT profile_views FROM Users WHERE username=:username');
      $stmt->bindParam("username", $username);
      $stmt->execute();
      $views = $stmt->fetchColumn();
      $views = $views + 1;

      $stmt = $pdo->prepare('UPDATE Users SET profile_views=:views WHERE username=:username');
      $stmt->bindParam("username", $username);
      $stmt->bindParam("views", $views);
      $stmt->execute();

      return $response->withJson($user, 302);
    } else {
      return $response->withStatus(404);
    }
});
