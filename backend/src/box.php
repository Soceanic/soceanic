<?php

// Routes for the box
// Get user's box
$app->get('/box/{username}', function ($request, $response, $args) {
    $pdo = $this->db;
    $username = $args['username'];

    // Ensure the $username field is populated
    if ( !isset($username) || empty($username) ){
       return $response->withStatus(418);
    }

    // Retrieve the user's posts
    $stmt = $pdo->prepare(
      'SELECT * FROM Box_Saves WHERE username=:username
       AND date_removed=NULL
       ORDER BY date_added DESC'
    );
    $stmt->bindParam("username", $username);
    $stmt->execute();
    $data = [];
    while($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $post;
    }

    return $response->withJson($data, 302);
});

// Get box on friend's profile
$app->get('/box/{username}/{friend}', function ($request, $response, $args) {
    $pdo = $this->db;
    $username = $args['username'];
    $friend = $args['friend'];

    // Ensure the $username field is populated
    if ( !isset($username) || empty($username) ||
         !isset($friend) || empty($friend)){
       return $response->withStatus(418);
    }

    // Retrieve the user's posts
    $stmt = $pdo->prepare(
      'SELECT * FROM Box_Saves WHERE username=:username
       AND post_creator=:friend
       AND date_removed=NULL
       ORDER BY date_added DESC'
    );
    $stmt->bindParam("username", $username);
    $stmt->bindParam("friend", $friend);
    $stmt->execute();
    $data = [];
    while($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $post;
    }

    return $response->withJson($data, 302);
});
