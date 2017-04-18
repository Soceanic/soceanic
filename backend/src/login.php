<?php
// Routes for login requests

$app->post('/user/login', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;
    $plain_password = $data->password;

    if( !isset($username) || !isset($plain_password) ||
        !empty($username) || !empty($plain_password)) {

      return $response->withStatus(418);
    }

    // First, verify that the credentials are valid
    $stmt = $pdo->prepare('SELECT password FROM Users WHERE username=:username');
    $stmt->bindParam("username", $username);
    $stmt->execute();
    $password = $stmt->fetchColumn();

    if($password) {
      $is_valid = password_verify($plain_password, $password);
      if($is_valid) {

        // Rehash the password if necessary
        $needs_rehash = password_needs_rehash($password, PASSWORD_DEFAULT);
        if($needs_rehash) {
          $password = password_hash($plain_password, PASSWORD_DEFAULT);
          $stmt = $pdo->prepare('UPDATE Users SET password=:password WHERE username=:username')
          $stmt->bindParam("username", $username);
          $stmt->bindParam("password", $password);
          $stmt->execute();
        }

        // Create JWT Token

      } else {
          return $response->withAddedHeader('WWWW-Authenticate', 'None')->withStatus(401);
      }
    } else {
      return $response->withStatus(404);
    }

    return $response->withStatus(201);
});
