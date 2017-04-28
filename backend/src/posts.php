<?php

// Routes for posts
$app->get('/post/{post_id}', function ($request, $response, $args) {
    $pdo = $this->db;
    $id = $request->getAttribute('post_id');

    if (!isset($id) || empty($id)) {
        return $response->withStatus(418);
    }

    // Find the post
    $stmt = $pdo->prepare('SELECT * FROM Posts WHERE post_id=:post_id');
    $stmt->bindParam("post_id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row)
    {
      return $response->withStatus(404);
    }

    $post_id = $row['post_id'];
    $data[] = "post" => json_encode($row);

    // Get all comments for this post
    $stmt = $pdo->prepare('SELECT * FROM Comments WHERE post_id=:post_id');
    $stmt->bindParam("post_id", $id);
    $stmt->execute();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = ["comment" => json_encode($row)];
    }


    return $response->withJson(json_encode($data, JSON_UNESCAPED_SLASHES), 302);
});

// Takes in a username and returns the user's posts
$app->get('/posts/{username}', function($request, $response, $args) {
    $pdo = $this->db;
    $username = $args['username'];

    // Ensure the $username field is populated
    if ( !isset($username) || empty($username) ){
	     return $response->withStatus(418);
    }

    // Retrieve the user's posts
    $posts_sql = $pdo->prepare(
    	'SELECT * FROM Posts WHERE username=:username ORDER BY date_created DESC'
    );
    $posts_sql->bindParam("username", $username);
    $posts_sql->execute();
    $data = [];
    while($post = $posts_sql->fetch(PDO::FETCH_ASSOC)) {
      $data[] = ["post" => json_encode($post)];
    }

    return $response->withJson(json_encode($data, JSON_UNESCAPED_SLASHES), 302);
});