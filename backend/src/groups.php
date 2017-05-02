<?php
// Add a user to a group
$app->put('/group/{username1}/{username2}/{group_id}', function ($request, $response, $args) {
    $pdo = $this->db;
    $username1 = $args['username1'];
    $username2 = $args['username2'];
    $group_id = $args['group_id'];

    if( !isset($username1) || !isset($username2) || !isset($group_id) ||
        empty($username1) || empty($username2) || empty($group_id)) {
      return $response->withStatus(418);
    }

    // First, find out if users are even friends
    $stmt = $pdo->prepare('SELECT * FROM Relationships WHERE username_1=:username1 AND username_2=:username2');
    $stmt->bindParam("username1", $username1);
    $stmt->bindParam("username2", $username2);
    $stmt->execute();
    $row = $stmt->fetch();

    if(!$row) {
      return $response->withStatus(404);
    }

    if($row['username_1'] == $username1) {
        $stmt = $pdo->prepare('UPDATE Groups SET group_id_2=:group_id, last_updated=CURRENT_TIMESTAMP
                               WHERE username_1=:username1 AND username_2=:username2');
        $stmt->bindParam("group_id", $group_id);
        $stmt->bindParam("username1", $username1);
        $stmt->bindParam("username2", $username2);
        $stmt->execute();

        return $response->withStatus(201);
    } elseif ($row['username_2'] == $username1) {
        $stmt = $pdo->prepare('UPDATE Groups SET group_id_1=:group_id, last_updated=CURRENT_TIMESTAMP
                               WHERE username_1=:username2 AND username_2=:username1');
        $stmt->bindParam("group_id", $group_id);
        $stmt->bindParam("username1", $username1);
        $stmt->bindParam("username2", $username2);
        $stmt->execute();

        return $response->withStatus(201);
    }

});

// Change group priority
$app->put('/group/priority/{username}/{priority}/{group_id}', function ($request, $response, $args) {
    $username = $args['username'];
    $priority = $args['priority'];
    $group_id = $args['group_id'];

    if( !isset($username) || !isset($priority) || !isset($group_id) ||
        empty($username) || empty($priority) || empty($group_id)) {
      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare('SELECT * FROM ');
});

// Delete group
$app->delete('/group/{username}/{group_id}', function ($request, $response, $args) {

});
