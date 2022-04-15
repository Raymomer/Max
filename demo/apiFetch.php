<?php

$dbTableName = "contest";
$searchDate;

if (isset($_GET['date'])) {

    $response = new res();

    $searchDate = $_GET['date'];
    $re = '/^\d{4}-\d{2}-\d{2}$/m';

    preg_match($re, $searchDate, $dateFormat);
    if (count($dateFormat) == 0) {
        $response->success = false;
        $response->error_message = "Date format is wrong";
    } else {

        $sql = "SELECT * FROM  $dbTableName WHERE date = '$dateFormat[0]' ;";
        $payload_data = dbRead($sql);
        if (count($payload_data) == 0) {
            $response->success = false;
            $response->error_message = "No data";
        }
        $response->payload = $payload_data;
    }


    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}



function dbRead($sql)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "contest";
    $json = array();

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "Connect failed";
    }

    foreach (mysqli_fetch_all(mysqli_query($conn, $sql)) as $row) {
        array_push($json, array(
            "no" => $row[0],
            "date" => $row[1],
            "type" => $row[2],
            "away_team" => $row[4],
            "time" => $row[3],
            "home_team" => $row[5],
            "lose" => $row[6],
            "win" => $row[7]
        ));
    }

    return $json;
}


class res
{
    public $success = TRUE;
    public $payload;
    public $error_message;

    public function __construct($success = TRUE)
    {
        $this->success = $success;
    }
}
