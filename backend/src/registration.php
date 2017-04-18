<?php
// Routes for the registration page requests

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

    // // Create verification link
    // $link
    //
    // require 'vendor/autoload.php';
    // use Mailgun\Mailgun;
    //
    // // Instantiate the client.
    // $mgClient = new Mailgun('key-66f9eee38890d4831259e636bc487711');
    // $domain = "soceanic.me";
    //
    // // Make the call to the client.
    // $result = $mgClient->sendMessage($domain, array(
    //     'from'    => 'soceanic <mailgun@soceanic.me>',
    //     'to'      => $first_name . ' ' . $last_name ' <' . $email . '>',
    //     'subject' => 'Verify Your Soceanic Account',
    //     'text'    => 'Click the following link to verify your account:\n\n' . $link
    // ));

    return $response->withStatus(201);

});
