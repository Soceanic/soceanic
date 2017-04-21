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

    if (isset($username)) {
      
    }
    
    if (isset($first_name)) {
      
    }
    
    if (isset($last_name)) {
      
    }
    
    if (isset($birthday)) {
      
    }
    
    if (isset($email)) {
      
    }
    
    if (isset($password)) {
      // Hash the password
      $password = password_hash($plain_password, PASSWORD_DEFAULT);
    }
    
    // Add the entry to the array once all the fields have been verified
    $stmt = $pdo->prepare(
      "INSERT INTO Users (username, first_name, last_name, email,
       password, birthday, verified, last_login, date_joined, last_updated, profile_views)
       VALUES (:username, :first_name, :last_name, :email, :password,
         :birthday, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 0)"
      );
    $stmt->bindParam("username", $username);
    $stmt->bindParam("first_name", $first_name);
    $stmt->bindParam("last_name", $last_name);
    $stmt->bindParam("email", $email);
    $stmt->bindParam("password", $password);
    $stmt->bindParam("birthday", $birthday);
    $stmt->execute();

    return $response->withStatus(201);
});
