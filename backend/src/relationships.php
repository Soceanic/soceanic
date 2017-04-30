<?php
// Get status of a relationship
$app->get('/status/{username1}/{username2}', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    // Check if json is valid
    if( !isset($args['username1']) || !isset($args['username2']) ||
        empty($args['username1']) || empty($args['username2'])) {

      return $response->withStatus(418);
    }

    $username1 = $args['username1'];
    $username2 = $args['username2'];

    $stmt = $pdo->prepare(
      "SELECT status FROM Relationships WHERE username_1=:username1
       AND username_2=:username2"
      );

    $stmt->bindParam("username1", $username1);
    $stmt->bindParam("username2", $username2);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$data) {
      $data = array('status' => 4);
    }

    return $response->withJson($data, 200);
});

// Getting a list of the user's incoming requests
$app->get('/requests/{username}', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    // Check if json is valid
    if( !isset($args['username']) || empty($args['username'])) {
      return $response->withStatus(418);
    }

    $username = $args['username'];

    $stmt = $pdo->prepare('SELECT username_1, status, date_sent FROM Relationships
      WHERE username_2=:username');

    $stmt->bindParam("username", $username);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $response->withJson($data, 200);
});
