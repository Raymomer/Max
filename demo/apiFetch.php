<?php
require 'db/read.php';
$dbTableName = "contest";
$searchDate;

$response = new res();


if (isset($_GET['date'])) {

    // sleep(5);


    $searchDate = $_GET['date'];
    $re = '/^[12]\d{3}-[01]\d-[0123]\d$/m';

    preg_match($re, $searchDate, $dateFormat);

    if (count($dateFormat) == 0) {
        $response->error_message = "Date format is wrong";
    } else {

        $sql = "SELECT * FROM  $dbTableName WHERE date = '$dateFormat[0]' ";

        if (isset($_GET['team'])) {
            $searchTeam = $_GET['team'];
            $sql .= "AND (away_team LIKE '%$searchTeam%'  OR home_team LIKE '%$searchTeam%' )";
        }

        $payload_data = dbRead($sql);
        if (count($payload_data) == 0) {
            $response->error_message = "No data";
        }
        $response->success = TRUE;
        $response->payload = $payload_data;
    }
} else {

    $response->error_message = "Please enter date";
}


$response->req();




class res
{
    public $success = FALSE;
    public $payload = [];
    public $error_message;

    public function __construct($success = FALSE, $payload = [])
    {
        $this->success = $success;
        $this->payload = $payload;
    }

    public function req()
    {
        $json = array(
            'success' => $this->success,
            'payload' => $this->payload,
            'error_message' => $this->error_message,

        );
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json, JSON_UNESCAPED_UNICODE);
        return;
    }
}
