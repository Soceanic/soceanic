<?php
// Assistive functions
require '../vendor/autoload.php';

use Aws\Credentials\CredentialProvider;
use Aws\S3\S3Client;
use Aws\Sdk;
use \Firebase\JWT\JWT;
use \Mailgun\Mailgun;

function validated_user($jwt) {
  $username = NULL;

  try {
    $decoded = JWT::decode($jwt, $_SERVER['SECRET_KEY'], array('HS256'));
    $username = $decoded->username;
  } catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
  }

  return $username;
}

function send_verification($username, $email, $first_name, $last_name) {
  // Create verification link using a one time jwt
  $payload = array(
  "username" => $username,
  "email" => $email,
  "exp" => time() + (60 * 60)     // jwt expires in one hour
  );

  // encode the payload using our secretkey and return the token
  $token = JWT::encode($payload, $_SERVER['SECRET_KEY']);
  $link = 'http://localhost:8080/token/' . $token;

  // Instantiate the client.
  $mgClient = new \Mailgun\Mailgun($_SERVER['MAILGUN_KEY'], new \Http\Adapter\Guzzle6\Client());
  $domain = "soceanic.me";

  $html = "<html><p>Click the following link to verify your account:</p><br>
  <a href='" . $link . "'>Click me!</a></html>";
  // Make the call to the client.
  $result = $mgClient->sendMessage($domain, array(
      'from'    => 'Soceanic <mailgun@soceanic.me>',
      'to'      => $first_name . ' ' . $last_name . ' <' . $email . '>',
      'subject' => 'Verify Your Soceanic Account',
      'html'    => $html,
  ));
}

// Upload image to aws bucket
function upload_image($path, $name) {
  //Create a S3Client
  $aws = new Aws\Sdk();
  $provider = CredentialProvider::ini();
  // Cache the results in a memoize function to avoid loading and parsing
  // the ini file on every API operation.
  $provider = CredentialProvider::memoize($provider);
  $client = new S3Client([
    'region'      => 'us-east-2',
    'version'     => 'latest',
    'credentials' => $provider
  ]);

  $result = $client->putObject(array(
    'Bucket'     => 'images.soceanic.me',
    'Key'        => $name . '.png',
    'SourceFile' => $path,
  ));

  // We can poll the object until it is accessible
  $client->waitUntil('ObjectExists', array(
    'Bucket' => 'images.soceanic.me',
    'Key'    => $name . '.png',
  ));

  return $result['ObjectURL'];
}
