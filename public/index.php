<?php
declare(strict_types=1);


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/') {
    require_once __DIR__ . '/../app/controllers/main.php';
} elseif ($uri === '/about') {
    require_once __DIR__ . '/../app/controllers/about.php';
} elseif ($uri === '/events') {
    require_once __DIR__ . '/../app/controllers/events.php';
} else {
    http_response_code(404);
    echo '404 - Page not found.';
}

