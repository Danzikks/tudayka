<?php
declare(strict_types=1);
define('FUNCS', __DIR__ . '/functions.php'); // это нужно куда то вынести



function DD(mixed $data) : void
{
    var_dump($data);
    die();
}