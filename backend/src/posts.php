<?php

// Routes for posts
$app->post('/posts', function ($request, $response, $args) {
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
      return $response->withStatus(418);
    }
    
     // Add the entry to the DB once all the fields have been verified
    $stmt = $pdo->prepare(
      "INSERT INTO Posts (username, title, text, attachment,
       likes, date_created, last_updated)
       VALUES (:username, :title, :text, :attachment, :0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
      );
      
    $stmt->bindParam("username", $username);
    $stmt->bindParam("title", $title);
    $stmt->bindParam("text", $text);
    $stmt->bindParam("attachment", $attach);
    $stmt->execute();
    
    return $response->withStatus(201);
});
