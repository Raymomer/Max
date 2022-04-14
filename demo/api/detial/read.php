<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include '../config/database.php';
include_once '../objects/detial.php';


$database = new Database();
$db = $database->getConnection();

$detial = new Detial($db);


$stmt = $detial->read();
$num = count($stmt);


if ($num > 0) {

    $detial_arr = array();
    $detial_arr["records"] = $stmt;

    http_response_code(200);
    echo json_encode($detial_arr);
} else {

    http_response_code(404);

    echo json_encode(
        array("message" => "No Detial found.")
    );
}
