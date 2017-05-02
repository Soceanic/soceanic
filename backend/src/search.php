<?php

$app->get('/group/{term}', function ($request, $response, $args) {
    $pdo = $this->db;
    $term = $args['term'];

    if( !isset($term) || empty($term)) {
      return $response->withStatus(418);
    }

    $data = [];
    $stmt = $pdo->prepare('SELECT username, first_name, last_name, profile_pic
                           FROM Users
                           WHERE username like "%:term%"
                           OR first_name like "%:term%"
                           OR last_name like "%:term%"
                           AND verified=1');
    $stmt->bindParam("term", $term);
    $stmt->execute();
    while($row = $stmt->fetch(FETCH::ASSOC)) {
      $data[] = $row;
    }

    $stmt = $pdo->prepare('SELECT post_id, username, title, text, likes, date_created, last_updated
                           FROM Posts
                           WHERE username like "%:term%"
                           OR title like "%:term%"
                           OR text like "%:term%"');
    $stmt->bindParam("term", $term);
    $stmt->execute();
    while($row = $stmt->fetch(FETCH::ASSOC)) {
      $data[] = $row;
    }

    return $response->withJson($data, 302);
});
