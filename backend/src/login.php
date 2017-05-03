<?php
// Routes for login requests

use \Firebase\JWT\JWT;

$app->post('/user/login', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;
    $plain_password = $data->password;

    if( !isset($username) || !isset($plain_password) ||
        empty($username) || empty($plain_password)) {

      return $response->withStatus(418);
    }

    // First, verify that the credentials are valid
    $stmt = $pdo->prepare('SELECT password, verified FROM Users WHERE username=:username');
    $stmt->bindParam("username", $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $password = $row['password'];
    $verified = $row['verified'];

    if($password) {
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

        if($verified == 1) {
          // Create the token as an array
          $payload = [
              'username' => $username,     // User name
          ];

          $token = JWT::encode($payload, $_SERVER['SECRET_KEY'], array('HS256'));
          $json = json_encode(array(
                                "jwt" => $token
                              ));

          $stmt = $pdo->prepare('UPDATE Users SET last_login=CURRENT_TIMESTAMP WHERE username=:username');
          $stmt->bindParam("username", $username);
          $stmt->execute();

          return $response->withJson($json, 200);
        } else {
          return $response->withAddedHeader('WWWW-Authenticate', 'None')->withStatus(403);
        }
      } else {
          return $response->withAddedHeader('WWWW-Authenticate', 'None')->withStatus(401);
      }
    } else {
      return $response->withStatus(404);
    }

    // return $response->withStatus(201); 💩
});

// Image uploading
$app->post('/upload', function ($request, $response, $args) {
    $pdo = $this->db;
    $file = $request->getUploadedFiles()['image'];
    $uploadFileName = $file->getClientFilename();
    $file->moveTo("../../" . $uploadFileName);

    // Generate an uuid for the file name
    $stmt = $pdo->prepare('SELECT UUID()');
    $stmt->execute();
    $uuid = $stmt->fetchColumn();

    $data = array("path" => upload_image("../../" . $uploadFileName, $uuid));
    unlink("../../" . $uploadFileName);
    return $response->withJson($data, 201);
});
