<?php

// Original Answer

header('Content-Type: application/json');

$request = file_get_contents('php://input');

$fp = file_put_contents( 'request.log', $request );




