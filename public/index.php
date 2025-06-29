<?php
declare(strict_types=1);
require_once '../vendor/autoload.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/') {
    require_once __DIR__ . '/../src/controllers/main.php';
} elseif ($uri === '/about') {
    require_once __DIR__ . '/../src/controllers/about.php';
} elseif ($uri === '/events') {
    require_once __DIR__ . '/../src/controllers/events.php';
} else {
    http_response_code(404);
    echo '404 - Page not found.';
}

