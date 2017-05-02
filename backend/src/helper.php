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
  $link = 'http://soceanic.me/api/token/' . $token;

  // Instantiate the client.
  $mgClient = new \Mailgun\Mailgun($_SERVER['MAILGUN_KEY'], new \Http\Adapter\Guzzle6\Client());
  $domain = "soceanic.me";

  // Make the call to the client.
  $result = $mgClient->sendMessage($domain, array(
      'from'    => 'Soceanic <mailgun@soceanic.me>',
      'to'      => $first_name . ' ' . $last_name . ' <' . $email . '>',
      'subject' => 'Verify Your Soceanic Account',
      'html'    => create_email($link),
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

function create_email($link) {
  return '<style>
          /***
          User Profile Sidebar by @keenthemes
          A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: http://j.mp/metronictheme
          Licensed under MIT
          ***/

          body {
              padding: 0;
              margin: 0;
          }

          html { -webkit-text-size-adjust:none; -ms-text-size-adjust: none;}
          @media only screen and (max-device-width: 680px), only screen and (max-width: 680px) {
              *[class="table_width_100"] {
          		width: 96% !important;
          	}
          	*[class="border-right_mob"] {
          		border-right: 1px solid #dddddd;
          	}
          	*[class="mob_100"] {
          		width: 100% !important;
          	}
          	*[class="mob_center"] {
          		text-align: center !important;
          	}
          	*[class="mob_center_bl"] {
          		float: none !important;
          		display: block !important;
          		margin: 0px auto;
          	}
          	.iage_footer a {
          		text-decoration: none;
          		color: #929ca8;
          	}
          	img.mob_display_none {
          		width: 0px !important;
          		height: 0px !important;
          		display: none !important;
          	}
          	img.mob_width_50 {
          		width: 40% !important;
          		height: auto !important;
          	}
          }
          .table_width_100 {
          	width: 680px;
          }
          </style>

          <!--
          Responsive Email Template by @keenthemes
          A component of Metronic Theme - #1 Selling Bootstrap 3 Admin Theme in Themeforest: http://j.mp/metronictheme
          Licensed under MIT
          -->

          <div id="mailsub" class="notification" align="center">

          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 320px;"><tr><td align="center" bgcolor="#eff3f8">


          <!--[if gte mso 10]>
          <table width="680" border="0" cellspacing="0" cellpadding="0">
          <tr><td>
          <![endif]-->

          <table border="0" cellspacing="0" cellpadding="0" class="table_width_100" width="100%" style="max-width: 680px; min-width: 300px;">
              <tr><td>
          	<!-- padding -->
          	</td></tr>
          	<!--header -->
          	<tr><td align="center" bgcolor="#ffffff">
          		<!-- padding -->
          		<table width="90%" border="0" cellspacing="0" cellpadding="0"><div style="height: 30px; line-height: 30px; font-size: 10px;"></div>
          			<tr><td align="center">
          			    		<a href="#" target="_blank" style="color: #596167; font-family: Arial, Helvetica, sans-serif; float:left; width:100%; padding:20px;text-align:center; font-size: 13px;">
          									<font face="Arial, Helvetica, sans-seri; font-size: 13px;" size="3" color="#596167">
          									<img src="https://s3.us-east-2.amazonaws.com/images.soceanic.me/email_banner.png" width="120" alt="Metronic" border="0"  /></font></a>
          					</td>
          					<td align="right">
          				<!--[endif]--><!--

          			</td>
          			</tr>
          		</table>
          		<!-- padding --><div style="height: 50px; line-height: 50px; font-size: 10px;"></div>
          	</td></tr>
          	<!--header END-->

          	<!--content 1 -->
          	<tr><td align="center" bgcolor="#fbfcfd">
          		<table width="90%" border="0" cellspacing="0" cellpadding="0">
          			<tr><td align="center">
          				<!-- padding --><div style="height: 60px; line-height: 60px; font-size: 10px;"></div>
          				<div style="line-height: 44px;">
          					<font face="Arial, Helvetica, sans-serif" size="5" color="#57697e" style="font-size: 34px;">
          					<span style="font-family: Arial, Helvetica, sans-serif; font-size: 34px; color: #57697e;">
          						Soceanic
          					</span></font>
          				</div>
          				<!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"></div>
          			</td></tr>
          			<tr><td align="center">
          				<div style="line-height: 24px;">
          					<font face="Arial, Helvetica, sans-serif" size="4" color="#57697e" style="font-size: 15px;">
          					<span style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #57697e;">
          						Welcome to Soceanic, please click the button below to verify your email address.
          					</span></font>
          				</div>
          				<!-- padding --><div style="height: 40px; line-height: 40px; font-size: 10px;"></div>
          			</td></tr>
          			<tr><td align="center">
          				<div style="line-height: 24px;">
          					<a href="' . $link . '" target="_blank" class="btn btn-danger block-center">
          					    Verify Email
          					</a>
          				</div>
          				<!-- padding --><div style="height: 60px; line-height: 60px; font-size: 10px;"></div>
          			</td></tr>
          		</table>
          	</td></tr>
          	<!--content 1 END-->


          	<!--footer -->
          	<tr><td class="iage_footer" align="center" bgcolor="#ffffff">


          		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          			<tr><td align="center" style="padding:20px;flaot:left;width:100%; text-align:center;">
          				<font face="Arial, Helvetica, sans-serif" size="3" color="#96a5b5" style="font-size: 13px;">
          				<span style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #96a5b5;">
          					2015 © Metronic. ALL Rights Reserved.
          				</span></font>
          			</td></tr>
          		</table>


          	</td></tr>
          	<!--footer END-->
          	<tr><td>

          	</td></tr>
          </table>
          <!--[if gte mso 10]>
          </td></tr>
          </table>
          <![endif]-->

          </td></tr>
          </table>';
}
