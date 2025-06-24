<?php
declare(strict_types=1);


if ($_SERVER['REQUEST_URI'] === '/') {
    require_once __DIR__ . '/../app/controllers/main.php';
} elseif ($_SERVER['REQUEST_URI'] === '/about') {
    require_once __DIR__ . '/../app/controllers/about.php';
}