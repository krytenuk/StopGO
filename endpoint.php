<?php

use Kryten\Db;

require './vendor/autoload.php';

$response = new stdClass();
$response->full_name = '';
$response->department = [];

header("Content-Type: application/json");

try {
    $database = new Db();
} catch (Exception $exception) {
    die(json_encode($response));
}

$employee = $database->checkRfidCard(filter_input(INPUT_GET, 'cn'));

if ($employee === null) {
    die(json_encode($response));
}

die(json_encode($employee));