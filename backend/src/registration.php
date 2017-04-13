<?php
// Routes for the registration page requests

$app->post('/user', function ($request, $response, $args) {
    $data = $request->getParsedBody();
    $syntax_violated = False;

    $username = $data['username'];
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $birthday = $data['birthday'];
    $email = $data['email'];
    $plain_password = $data['password'];

    # Check if json is valid
    if( !isset($username) || !isset($first_name) || !isset($last_name) ||
        !isset($birthday) || !isset($email) || !isset($plain_password) ||) {
      return $response->withStatus(418);
    }

    # Check if username already exists
    $stmt = $pdo->prepare('SELECT * FROM Users WHERE username=?');
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row)
    {
      return $response->withStatus(302);
    }

    // Hash the password
    $password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Add the entry to the array once all the fields have been verified
    $stmt = $pdo->prepare(
      'INSERT INTO Users (username, first_name, last_name, email,
       password, birthday, date_joined) VALUES (username=?, first_name=?,
       last_name=?, email=?, password=?, birthday=?, TIMESTAMP)'
      )

    $stmt->execute([$username, $first_name, $last_name, $email, $password,
                    $birthday]);

    // Return if inserted correctly
    $stmt = $pdo->prepare('SELECT * FROM Users WHERE username=?');
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    return $response->withStatus(201);

})
