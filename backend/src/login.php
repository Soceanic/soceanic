<?php
// Routes for login requests

$app->post('/user/login', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    // First, verify that the credentials are valid

    return $response->withStatus(201);
});
