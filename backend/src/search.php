<?php

$app->get('/search/{term}', function ($request, $response, $args) {
    $pdo = $this->db;
    $term = $args['term'];

    if( !isset($term) || empty($term)) {
      return $response->withStatus(418);
    }

    $data = [];
    $term = '%' . $term . '%';
    $stmt = $pdo->prepare('SELECT username
                           FROM Users
                           WHERE username like :term
                           OR first_name like :term
                           OR last_name like :term
                           AND verified=1');
    $stmt->bindParam("term", $term);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $row;
    }

    return $response->withJson($data, 200);
});
