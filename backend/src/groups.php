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

// Get a user's groups and the friends in each group by priority
$app->get('/group/{username}', function ($request, $response, $args) {
    $pdo = $this->db;
    $username = $args['username'];

    if( !isset($username) || empty($username)) {
      return $response->withStatus(418);
    }

    // Gets the highest priority of this user's groups
    $stmt = $pdo->prepare('SELECT MAX(priority) FROM Groups WHERE username=:username');
    $stmt->bindParam("username", $username);
    $stmt->execute();
    $priority = $stmt->fetchColumn();

    $data = [];

    // Cycles through each priority starting from the highest and gets the users in each group
    while($priority >= 0) {
      $stmt = $pdo->prepare('SELECT group_name, group_id FROM Groups WHERE username=:username AND priority=:priority');
      $stmt->bindParam("username", $username);
      $stmt->bindParam("priority", $priority);
      $stmt->execute();

      while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
        $group_name = $row['group_name'];
        $group_id = $row['group_id'];

        $stmt = $pdo->prepare('SELECT first_name, last_name, username, profile_pic
                               FROM Users WHERE usename IN (SELECT username_1
                                                            FROM Relationships
                                                            WHERE username_2=:username
                                                            AND status=1
                                                            AND group_id_1=:group_id)
                                          OR username IN (SELECT username_2
                                                          FROM Relationships
                                                          WHERE username_1=:username
                                                          AND status=1
                                                          AND group_id_2=:group_id)
                                          ORDER BY last_name ASC');
        $stmt->bindParam("username", $username);
        $stmt->bindParam("group_id", $group_id);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }

        $priority = $priority - 1;
      }
    }

    return $response->withJson($data, 200);
});

// Change group priority
// $app->put('/group/priority/{username}/{priority}/{group_id}', function ($request, $response, $args) {
//     $pdo = $this->db;
//     $username = $args['username'];
//     $priority = $args['priority'];
//     $group_id = $args['group_id'];
//
//     if( !isset($username) || !isset($priority) || !isset($group_id) ||
//         empty($username) || empty($priority) || empty($group_id)) {
//       return $response->withStatus(418);
//     }
//
//     $stmt = $pdo->prepare('SELECT * FROM ');
// });

// Delete group
$app->delete('/group/{username}/{group_id}', function ($request, $response, $args) {
  $pdo = $this->db;
  $username = $args['username'];
  $group_id = $args['group_id'];

  if( !isset($username) || !isset($group_id) ||
      empty($username) || empty($group_id)) {
    return $response->withStatus(418);
  }

  $stmt = $pdo->prepare('SELECT * FROM Groups WHERE username=:username AND group_id=:group_id');
  $stmt->bindParam("username", $username);
  $stmt->bindParam("group_id", $group_id);
  $stmt->execute();
  $row = $stmt->fetch();

  if(!$row) {
    return $response->withStatus(404);
  }

  $stmt = $pdo->prepare('DELETE FROM Groups WHERE username=:username AND group_id=:group_id');
  $stmt->bindParam("username", $username);
  $stmt->bindParam("group_id", $group_id);
  $stmt->execute();

  $stmt = $pdo->prepare('UPDATE Relationships SET group_id_1=NULL WHERE username IN (SELECT username_2
                                                                                     FROM Relationships
                                                                                     WHERE username_2=:username
                                                                                     AND group_id_1=:group_id
                                                                                     AND status=1)');
  $stmt->bindParam("username", $username);
  $stmt->bindParam("group_id", $group_id);
  $stmt->execute();

  $stmt = $pdo->prepare('UPDATE Relationships SET group_id_2=NULL WHERE username IN (SELECT username_1
                                                                                     FROM Relationships
                                                                                     WHERE username_1=:username
                                                                                     AND group_id_2=:group_id
                                                                                     AND status=1)');
  $stmt->bindParam("username", $username);
  $stmt->bindParam("group_id", $group_id);
  $stmt->execute();

  return $response->withStatus(204);
});
