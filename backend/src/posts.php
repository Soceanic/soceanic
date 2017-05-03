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
    $data[] = $row;

    // Get all comments for this post
    $stmt = $pdo->prepare('SELECT * FROM Comments WHERE post_id=:post_id');
    $stmt->bindParam("post_id", $id);
    $stmt->execute();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }


    return $response->withJson($data, 200);
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
    	'SELECT * FROM Posts WHERE username=:username ORDER BY post_id DESC'
    );
    $posts_sql->bindParam("username", $username);
    $posts_sql->execute();
    $data = [];
    while($post = $posts_sql->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $post;
    }

    return $response->withJson($data, 200);
});

// Returns all of the posts from all users
$app->get('/feed/{username}', function($request, $response, $args) {
    $pdo = $this->db;
    $username = $args['username'];

    $posts_sql = $pdo->prepare(
    	'SELECT * FROM Posts WHERE username IN ( SELECT username_1
                                               FROM Relationships
                                               WHERE username_2=:username
                                               AND status=1 )
                           OR username IN ( SELECT username_2
                                            FROM Relationships
                                            WHERE username_1=:username )
                           ORDER BY post_id DESC'
    );

    $posts_spl->bindParam("username", $username);
    $posts_sql->execute();
    $data = [];
    while($post = $posts_sql->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $post;
    }

    return $response->withJson($data, 200);
});

// Deletes a post
$app->delete('/delete/post/{post_id}', function($request, $response, $args) {
    $pdo = $this->db;
    $post_id = $args['post_id'];

    $posts_sql = $pdo->prepare(
    	'DELETE FROM Posts WHERE post_id=:post_id'
    );

    $posts_spl->bindParam("post_id", $post_id);
    $posts_sql->execute();

    return $response->withJson($data, 204);
});

// Returns the vote status for the post
$app->get('/vote/{username}/{post_id}', function($request, $response, $args) {
    $pdo = $this->db;
    $username = $args['username'];
    $post_id = $args['post_id'];

    $stmt = $pdo->prepare(
    	'SELECT upvote, downvote FROM Votes WHERE username=:username AND post_id=:post_id'
    );
    $stmt->bindParam("username", $username);
    $stmt->bindParam("post_id", $post_id);
    $stmt->execute();
    $status = $stmt->fetch();

    if(!$status) {
      return $response->withStatus(404);
    }

    if($status['upvote'] == 1) {
      $data = array("status" => "upvote" );
    } elseif ($status['downvote'] == 1) {
      $data = array("status" => "downvote" );
    } else {
      $data = array("status" => "none" );
    }

    return $response->withJson($data, 200);
});
