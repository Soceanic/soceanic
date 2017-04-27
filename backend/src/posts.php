<?php

// Routes for posts
$app->get('/post/{post_id}', function ($request, $response, $args) {
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
    $data[] = json_encode($row);

    // Get all comments for this post
    $stmt = $pdo->prepare('SELECT * FROM Comments WHERE post_id=:post_id');
    $stmt->bindParam("post_id", $id);
    $stmt->execute();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = json_encode($row);
    }


    return $response->withJson(json_encode($data), 302);
});
