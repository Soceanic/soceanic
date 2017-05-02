<?php
// Routes for the registration page requests
use \Firebase\JWT\JWT;

// Creating a new user
$app->post('/user', function ($request, $response, $args) {
    $pdo = $this->db;
    $json = $request->getBody();
    $data = json_decode($json);

    $username = $data->username;
    $first_name = $data->first_name;
    $last_name = $data->last_name;
    $birthday = $data->birthday;
    $email = $data->email;
    $plain_password = $data->password;

    // Check if json is valid
    if( !isset($username) || !isset($first_name) || !isset($last_name) ||
        !isset($birthday) || !isset($email) || !isset($plain_password) ||
        empty($username) || empty($first_name) || empty($last_name) ||
        empty($birthday) || empty($email) || empty($plain_password)) {

      return $response->withStatus(418);
    }

    // Check if username already exists
    $stmt = $pdo->prepare('SELECT * FROM Users WHERE username=:username');
    $stmt->bindParam("username", $username);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row)
    {
      return $response->withStatus(302);
    }

    // Check if email already exists
    $stmt = $pdo->prepare('SELECT * FROM Users WHERE email=:email');
    $stmt->bindParam("email", $email);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row)
    {
      return $response->withStatus(302);
    }

    // Hash the password
    $password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Add the entry to the array once all the fields have been verified
    $stmt = $pdo->prepare(
      "INSERT INTO Users (username, first_name, last_name, email,
       password, profile_pic, birthday, verified, last_login, date_joined, last_updated, profile_views)
       VALUES (:username, :first_name, :last_name, :email, :password, 'https://s3.us-east-2.amazonaws.com/images.soceanic.me/profile1.png',
         :birthday, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 0)"
      );

    $stmt->bindParam("username", $username);
    $stmt->bindParam("first_name", $first_name);
    $stmt->bindParam("last_name", $last_name);
    $stmt->bindParam("email", $email);
    $stmt->bindParam("password", $password);
    $stmt->bindParam("birthday", $birthday);
    $stmt->execute();

    send_verification($username, $email, $first_name, $last_name);

    return $response->withStatus(201);

});

// Validating a user's email
$app->get('/token/{token}', function ($request, $response, $args) {
    $pdo = $this->db;
    $username = validated_user($args['token']);
    if($username == NULL) {
      return $response->withAddedHeader('WWWW-Authenticate', 'None')->withStatus(401);
    }
    $stmt = $pdo->prepare('UPDATE Users SET verified=1, last_updated=CURRENT_TIMESTAMP WHERE username=:username');
    $stmt->bindParam("username", $username);
    $stmt->execute();

    return $response->withStatus(200);
});
