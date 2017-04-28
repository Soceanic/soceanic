<?php
require __DIR__ . '/helper.php';

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

// Returns all of the posts from all users
$app->post('/posts', function($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);
    $username = $data->username;

    $posts_sql = $pdo->prepare(
    	'SELECT * FROM Posts JOIN Relationships WHERE username_1=:username OR username_2=:username AND status=1 ORDER BY date_created DESC'
    );
    $posts_spl->bindParam("username", $username);
    $posts_sql->execute();
    $data = []
    while($post = $posts_sql->fetch(PDO::FETCH_ASSOC) {
      $data[] = json_encode($post);
    }

    return $response->withJson(json_encode($data), 302);
});
