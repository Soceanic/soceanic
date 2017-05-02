<?php
// Create a new group
$app->post('/group', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;
    $group = $data->group_name;

    if( !isset($username) || !isset($group) ||
        empty($username) || empty($group)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare('INSERT INTO Groups (username, group_name, priority, date_created, last_updated)
                           VALUES (:username, :group, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)');
    $stmt->bindParam("username", $username);
    $stmt->bindParam("group", $group);
    $stmt->execute();

    return $response->withStatus(201);
});
