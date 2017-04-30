<?php

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
      "SELECT * FROM Relationships WHERE username_1 IN (:username1, :username2)
       AND username_2 IN (:username1, :username2)"
      );

    $stmt->bindParam("username1", $username1);
    $stmt->bindParam("username2", $username2);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row)
    {
      return $response->withStatus(302);
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
$app->put('/respond', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username1 = $data->username1;
    $username2 = $data->username2;
    $status = $data->status;

    // Check if json is valid
    if( !isset($username1) || !isset($username2) ||
        empty($username1) || empty($username2)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare(
      "SELECT * FROM Relationships WHERE username_1=:username2
       AND username_2=:username1)"
      );

    $stmt->bindParam("username1", $username1);
    $stmt->bindParam("username2", $username2);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row)
    {
      return $response->withStatus(404);
    }

    $stmt = $pdo->prepare('UPDATE Relationships SET status=:status, last_updated=CURRENT_TIMESTAMP
      WHERE username_1=:username2 AND username_2=:username1');

    $stmt->bindParam("username1", $username1);
    $stmt->bindParam("username2", $username2);
    $stmt->bindParam("status", $status);
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
