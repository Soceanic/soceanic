<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/helper.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register all routes files ---------------------------------------------------------
require __DIR__ . '/../src/login.php';
require __DIR__ . '/../src/static_posts.php';
require __DIR__ . '/../src/static_relationships.php';
require __DIR__ . '/../src/static_box.php';
// Put all static routes above this line cause fuck php and slim in particular
require __DIR__ . '/../src/registration.php';
require __DIR__ . '/../src/box.php';
require __DIR__ . '/../src/posts.php';
require __DIR__ . '/../src/relationships.php';
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
