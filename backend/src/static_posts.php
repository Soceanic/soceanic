<?php

// Routes for posts
// Creating a new post
$app->post('/post', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    // First, verify that the credentials are valid
    $username = $data->username;
    $title = $data->title;
    $text = $data->text;
    $attach = $data->attachment;

    // Verify that username is set and exists
    if (!isset($username) || empty($username)) {
        return $response->withStatus(418);
    }

    // Check that username exists
    $stmt = $pdo->prepare('SELECT * FROM Users WHERE username=:username');
    $stmt->bindParam("username", $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$row)
    {
      return $response->withStatus(404);
    }

     // Add the entry to the DB once all the fields have been verified
    $stmt = $pdo->prepare(
      "INSERT INTO Posts (username, title, text,
       likes, date_created, last_updated)
       VALUES (:username, :title, :text, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
      );

    $stmt->bindParam("username", $username);
    $stmt->bindParam("title", $title);
    $stmt->bindParam("text", $text);
    $stmt->execute();

    if (!isset($attach) || empty($attach)) {
        return $response->withStatus(201);
    }

    // Find the post id
    $stmt = $pdo->prepare(
      "SELECT MAX(post_id) FROM Posts WHERE username=:username"
    );

    $stmt->bindParam("username", $username);
    $stmt->execute();
    $post_id = $stmt->fetchColumn();

    // Upload the file
    $link = upload_image($attach, $post_id);

    $stmt = $pdo->prepare('UPDATE Posts SET attachment=:link
                          WHERE username=:username AND post_id=:post_id');
    $stmt->bindParam("link", $link);
    $stmt->bindParam("username", $username);
    $stmt->bindParam("post_id", $post_id);
    $stmt->execute();

    return $response->withStatus(201);
});



// Upvote a post
$app->put('/post/upvote', function($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;
    $post_id = $data->post_id;

    if ( !isset($post_id) || empty($post_id) ) {
      return $response->withStatus(418);
    }

    $stmt = $pdo-prepare('SELECT likes FROM Posts WHERE post_id=:post_id');
    $stmt->bindParam("post_id", $post_id);
    $stmt->execute();
    $likes = $stmt->fetchColumn();

    if(!$likes) {
      return $response->withStatus(404);
    }

    $added = 0;
    $code = 200;

    $stmt = $pdo->prepare('SELECT upvote, downvote FROM Votes WHERE post_id=:post_id AND username=:username');
    $stmt->bindParam("username", $username);
    $stmt->bindParam("post_id", $post_id);
    $stmt->execute();
    $row = $stmt->fetch(FETCH::ASSOC);

    if($row) {
      if($row['upvote'] == 1) {
        $added = -1;

        $stmt = $pdo->prepare('UPDATE Votes SET upvote=0, last_updated=CURRENT_TIMESTAMP WHERE username=:username AND post_id=:post_id');
        $stmt->bindParam("username", $username);
        $stmt->bindParam("post_id", $post_id);
        $stmt->execute();

        $code = 302;
      } elseif ($row['downvote'] == 1 ) {
        $added = 2;

        $stmt = $pdo->prepare('UPDATE Votes SET upvote=1, downvote=0, last_updated=CURRENT_TIMESTAMP WHERE username=:username AND post_id=:post_id');
        $stmt->bindParam("username", $username);
        $stmt->bindParam("post_id", $post_id);
        $stmt->execute();

        $code = 201;
      }
    } else {
        $added = 1;
        $stmt = $pdo->prepare('INSERT INTO Votes (post_id, username, upvote, date_created, last_updated)
                               VALUES (:post_id, :username, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
        $stmt->bindParam("post_id", $post_id);
        $stmt->bindParam("username", $username);
        $stmt->execute();

        $code = 201;
    }

    $likes = $likes + $added;

    $stmt = $pdo->prepare('UPDATE Posts SET likes=:likes WHERE post_id=:post_id');
    $stmt->bindParam("likes", $likes);
    $stmt->bindParam("post_id", $post_id);
    $stmt->execute();

    return $response->withStatus($code);
});

// Downvote a post
$app->put('/post/downvote', function($request, $response, $args) {
  $pdo = $this->db;
  $json = $request->getBody();
  $data = json_decode($json);

  $username = $data->username;
  $post_id = $data->post_id;

  if ( !isset($post_id) || empty($post_id) ) {
    return $response->withStatus(418);
  }

  $stmt = $pdo-prepare('SELECT likes FROM Posts WHERE post_id=:post_id');
  $stmt->bindParam("post_id", $post_id);
  $stmt->execute();
  $likes = $stmt->fetchColumn();

  if(!$likes) {
    return $response->withStatus(404);
  }

  $added = 0;
  $code = 200;

  $stmt = $pdo->prepare('SELECT upvote, downvote FROM Votes WHERE post_id=:post_id AND username=:username');
  $stmt->bindParam("username", $username);
  $stmt->bindParam("post_id", $post_id);
  $stmt->execute();
  $row = $stmt->fetch(FETCH::ASSOC);

  if($row) {
    if($row['upvote'] == 1) {
      $added = -2;

      $stmt = $pdo->prepare('UPDATE Votes SET upvote=0, downvote=1 last_updated=CURRENT_TIMESTAMP WHERE username=:username AND post_id=:post_id');
      $stmt->bindParam("username", $username);
      $stmt->bindParam("post_id", $post_id);
      $stmt->execute();

      $code = 302;
    } elseif ($row['downvote'] == 1 ) {
      $added = 1;

      $stmt = $pdo->prepare('UPDATE Votes SET downvote=0, last_updated=CURRENT_TIMESTAMP WHERE username=:username AND post_id=:post_id');
      $stmt->bindParam("username", $username);
      $stmt->bindParam("post_id", $post_id);
      $stmt->execute();

      $code = 201;
    }
  } else {
      $added = 1;
      $stmt = $pdo->prepare('INSERT INTO Votes (post_id, username, downvote, date_created, last_updated)
                             VALUES (:post_id, :username, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
      $stmt->bindParam("post_id", $post_id);
      $stmt->bindParam("username", $username);
      $stmt->execute();

      $code = 201;
  }

  $likes = $likes + $added;

  $stmt = $pdo->prepare('UPDATE Posts SET likes=:likes WHERE post_id=:post_id');
  $stmt->bindParam("likes", $likes);
  $stmt->bindParam("post_id", $post_id);
  $stmt->execute();

  return $response->withStatus($code);
});
