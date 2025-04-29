<?php

// Original Answer

header('Content-Type: application/json');

$request = file_get_contents('php://input');

$update = json_decode($request);

$req_dump = print_r( $request, true );

$fp = file_put_contents( 'request.log', $req_dump );


