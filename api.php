<?php

include_once('config.php');
require_once('api/account.php');
require_once('api/transfer.php');
header('Content-Type: application/json');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$method = $_SERVER['REQUEST_METHOD'];
if (array_key_exists('PATH_INFO', $_SERVER)) {
    $args = explode('/', trim($_SERVER['PATH_INFO'],'/'));
}
else {
    $args = [];
}
$input = json_decode(file_get_contents('php://input'),true);

$request = array();
$request['method'] = $method;
$request['args'] = $args;
if ($input == null) {
    $request['input'] = array();
}
else {
    $request['input'] = $input;
}

$response = array();

if (count($args) == 0) {
    $response['status'] = 404;
    $response['message'] = "API path not found";
}
else {
    if ($args[0] == 'accounts') {
        $response = (new API_ACCOUNTS)->execute($request, $conn);
    }
    else if ($args[0] == 'transfer') {
        $response = (new API_TRANSFER)->execute($request, $conn);
    }
    else {
        $response['status'] = 404;
        $response['message'] = "API path not found";
    }
}

echo json_encode($response);
