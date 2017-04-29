<?php
// Routes for the registration page requests
// Any time

// Creating a new friend request
$app->post('/friend', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username1 = $data->username1;
    $username2 = $data->username2;

    // Check if json is valid
    if( !isset($username1) || !isset($username2) ||
        empty($username1) || empty($username2)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare(
      "INSERT INTO Relationships (username_1, username_2, status, date_sent,
         last_updated, group_id_1, group_id_2)
       VALUES (:username1, :username2, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,
         0, 0)"
      );

    $stmt->bindParam("username1", $username1);
    $stmt->bindParam("username2", $username2);
    $stmt->execute();

    return $response->withStatus(201);
});

// Changing the status of a relationship
// Add friend
$app->put('/friend', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;

    // Check if json is valid
    if( !isset($username) || !isset($username)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare('UPDATE Relationships SET status=1, last_updated=CURRENT_TIMESTAMP
      WHERE username_2=:username');

    $stmt->bindParam("username", $username);
    $stmt->execute();

    return $response->withStatus(200);

});

// Ignore
$app->put('/ignore', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;

    // Check if json is valid
    if( !isset($username) || !isset($username)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare('UPDATE Relationships SET status=2, last_updated=CURRENT_TIMESTAMP
      WHERE username_2=:username');

    $stmt->bindParam("username", $username);
    $stmt->execute();

    return $response->withStatus(200);
});

// Block a friend
$app->put('/block', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;

    // Check if json is valid
    if( !isset($username) || !isset($username)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare('UPDATE Relationships SET status=3, last_updated=CURRENT_TIMESTAMP
      WHERE username_2=:username');

    $stmt->bindParam("username", $username);
    $stmt->execute();

    return $response->withStatus(200);

});

// Block a stranger
$app->post('/block', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username1 = $data->username1;
    $username2 = $data->username2;

    // Check if json is valid
    if( !isset($username1) || !isset($username2) ||
        empty($username1) || empty($username2)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare(
      "INSERT INTO Relationships (username_1, username_2, status, date_sent,
         last_updated, group_id_1, group_id_2)
       VALUES (:username1, :username2, 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP,
         0, 0)"
      );

    $stmt->bindParam("username1", $username1);
    $stmt->bindParam("username2", $username2);
    $stmt->execute();

    return $response->withStatus(201);
});

// Getting a list of the user's incoming requests
$app->get('/requests', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;

    // Check if json is valid
    if( !isset($username) || !isset($username)) {
      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare('SELECT username_1, status, date_sent FROM Relationships
      WHERE username_2=:username');

    $stmt->bindParam("username", $username);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $response->withJson($data, 200);
});

// Get status of a relationship
$app->get('/status', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username1 = $data->username1;
    $username2 = $data->username2;

    // Check if json is valid
    if( !isset($username1) || !isset($username2) ||
        empty($username1) || empty($username2)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare(
      "SELECT status FROM Relationships WHERE username_1=:username1
       AND username_2=:username2"
      );

    $stmt->bindParam("username1", $username1);
    $stmt->bindParam("username2", $username2);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$data) {
      $data = array('status' => 5);
    }

    return $response->withJson($data, 200);
});
