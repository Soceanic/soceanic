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
    $bio = $data->bio;
    $plain_password = $data->password;
    $profile_pic = $data->profile_pic;
    $bg_pic = $data->bg_pic;

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

    if (isset($bio) && !empty($bio)) {
      $stmt = $pdo->prepare(
          "UPDATE Users SET bio=:bio WHERE username=:username"
          );
      $stmt->bindParam("username", $username);
      $stmt->bindParam("bio", $bio);
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

    if (isset($profile_pic) && !empty($profile_pic)) {
      $stmt = $pdo->prepare(
          "UPDATE Users SET profile_pic=:profile_pic WHERE username=:username"
          );
      $stmt->bindParam("username", $username);
      $stmt->bindParam("profile_pic", $profile_pic);
      $stmt->execute();
    }

    if (isset($bg_pic) && !empty($bg_pic)) {
      $stmt = $pdo->prepare(
          "UPDATE Users SET bg_pic=:bg_pic WHERE username=:username"
          );
      $stmt->bindParam("username", $username);
      $stmt->bindParam("bg_pic", $bg_pic);
      $stmt->execute();
    }

    return $response->withStatus(200);
});
