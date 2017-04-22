<?php
// Assistive functions
require '../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Sdk;
use \Firebase\JWT\JWT;

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

// Upload image to aws bucket
function upload_image($path, $name) {
  //Create a S3Client
  $aws = new Aws\Sdk();
  $client = $sdk->createS3();

  $result = $client->putObject(array(
    'Bucket'     => $bucket,
    'Key'        => $name . '.png',
    'SourceFile' => $path,
  ));

  // We can poll the object until it is accessible
  $client->waitUntil('ObjectExists', array(
    'Bucket' => $this->bucket,
    'Key'    => $name . '.png',
  ));

  return $result['ObjectURL'];
}
