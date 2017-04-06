<?php

$app->get('/users/id', function($username = null) {
    $sqlGetUser = $pdo->prepare(
      'SELECT * FROM users WHERE username = ?'
      );
    $sqlGetUser->execute([$username]);
    $user = $sqlGetUser->fetch();  
  
    $sqlVerifyUser = $pdo->prepare(
      'SELECT COUNT(*) FROM users WHERE username = ?'
      );
    $sqlVerifyUser->execute([$username]);
    $userCount = $sqlVerifyUser->fetch(); 
  
    if (!$username->isEmpty()) {
        if ($userCount > 0) {
           
        }
    }
  }
);
