<?php

// Routes for the box
$app->post('/post/save', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;
    $post_id = $data->post_id;

    // Verify that username is set and exists
    if (!isset($username) || !isset($post_id) ||
         empty($username) || empty($post_id)) {
        return $response->withStatus(418);
    }

    // Check that the post exists
    $stmt = $pdo->prepare('SELECT * FROM Posts WHERE post_id=:post_id');
    $stmt->bindParam("post_id", $post_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$row)
    {
      return $response->withStatus(404);
    }

    $creator = $row['username'];

    $stmt = $pdo->prepare('INSERT INTO Box_Saves (username, post_id, post_creator, date_added)
                           VALUES (:username, :post_id, :creator, CURRENT_TIMESTAMP)');
    $stmt->bindParam("username", $username);
    $stmt->bindParam("post_id", $post_id);
    $stmt->bindParam("creator", $creator);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $response->withStatus(201);
});

// Remove post from box
$app->put('/post/remove', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;
    $post_id = $data->post_id;

    // Verify that username is set and exists
    if (!isset($username) || !isset($post_id) ||
         empty($username) || empty($post_id)) {
        return $response->withStatus(418);
    }

    // Check that the post exists in the box
    $stmt = $pdo->prepare('SELECT * FROM Box_Saves WHERE username=:username AND post_id=:post_id');
    $stmt->bindParam("username", $username);
    $stmt->bindParam("post_id", $post_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$row)
    {
      return $response->withStatus(404);
    }

    $stmt = $pdo->prepare('UPDATE Box_Saves SET date_removed=CURRENT_TIMESTAMP
                           WHERE username=:username AND post_id=:post_id');
    $stmt->bindParam("username", $username);
    $stmt->bindParam("post_id", $post_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $response->withStatus(200);
});
