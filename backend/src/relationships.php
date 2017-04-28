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
      WHERE username=:username');

    $stmt->bindParam("username", $username);
    $stmt->execute();

    return $response->withStatus(200);

});

// Ignore
$app->put('/ignore', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

// Block
});

$app->post('/block', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

// Getting a list of the user's incoming or outgoing requests
$app->get('/requests', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

});

// Get status of a relationship
$app->post('/status', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

});
