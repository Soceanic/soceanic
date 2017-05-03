<?php
// Routes for the registration page requests
$app->put('/user', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);
    $username = $data->username;
    $first_name = $data->first_name;
    $last_name = $data->last_name;
    $birthday = $data->birthday;
    $email = $data->email;
    $plain_password = $data->password;

    if (isset($first_name) && !empty($first_name)) {
      $stmt = $pdo->prepare(
          "UPDATE Users SET first_name=:first_name WHERE username=:username"
          );
      $stmt->bindParam("username", $username);
      $stmt->bindParam("first_name", $first_name);
      $stmt->execute();
    }

    if (isset($last_name) && !empty($last_name)) {
      $stmt = $pdo->prepare(
          "UPDATE Users SET last_name=:last_name WHERE username=:username"
          );
      $stmt->bindParam("username", $username);
      $stmt->bindParam("last_name", $last_name);
      $stmt->execute();
    }

    if (isset($birthday) && !empty($birthday)) {
      $stmt = $pdo->prepare(
          "UPDATE Users SET birthday=:birthday WHERE username=:username"
          );
      $stmt->bindParam("username", $username);
      $stmt->bindParam("birthday", $birthday);
      $stmt->execute();
    }

    if (isset($email) && !empty($email)) {
      $stmt = $pdo->prepare(
          "UPDATE Users SET email=:email WHERE username=:username"
          );
      $stmt->bindParam("username", $username);
      $stmt->bindParam("email", $email);
      $stmt->execute();
    }

    if (isset($plain_password) && !empty($plain_password)) {
      $is_valid = password_verify($plain_password, $password);
      if($is_valid) {
        // Rehash the password if necessary
        $needs_rehash = password_needs_rehash($password, PASSWORD_DEFAULT);
        if($needs_rehash) {
          $password = password_hash($plain_password, PASSWORD_DEFAULT);
          $stmt = $pdo->prepare('UPDATE Users SET password=:password WHERE username=:username');
          $stmt->bindParam("username", $username);
          $stmt->bindParam("password", $password);
          $stmt->execute();
        }
      }
    }

    return $response->withStatus(201);
});

$app->delete('/user', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);
    $username = $data->username;

    if( !isset($username) || empty($username)) {

      return $response->withStatus(418);
    }

    $stmt = $pdo->prepare(
        "DELETE FROM Users WHERE username=:username"
        );
    $stmt->bindParam("username", $username);
    $stmt->execute();

    return $response->withStatus(204);
});
