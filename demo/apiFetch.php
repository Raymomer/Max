<?php
include  'db/db.php';
// $db = new DB($servername = "localhost", $username = "root", $password = "", $dbname = "contest");
$dbTableName = "contest";
$searchDate;

if (isset($_GET['date'])) {

    $searchDate = $_GET['date'];
    $re = '/^\d{4}-\d{2}-\d{2}$/m';

    preg_match($re, $searchDate, $dateFormat);
    if (count($dateFormat) == 0) {
        echo "Date format is wrong";
        return;
    }

    $sql = "SELECT * FROM  $dbTableName WHERE date = '$dateFormat[0]' ;";
    $payload_data = dbRead($sql);

    $successFlag = true;

    if (count($payload_data) == 0) {
        $successFlag = false;
    }
    $response = array(
        "success" =>  $successFlag,
        "payload" => $payload_data,

    );
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}



function dbRead($sql)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "contest";
    $conn;

    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        echo "Connect failed";
    }


    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "Connect failed";
    }

    $json = array();
    foreach (mysqli_fetch_all(mysqli_query($conn, $sql)) as $row) {

        array_push($json, array(
            "no" => $row[0],
            "date" => $row[1],
            "type" => $row[2],
            "away_team" => $row[3],
            "time" => $row[4],
            "home_team" => $row[5],
            "lose" => $row[6],
            "win" => $row[7]
        ));
    }

    return $json;
}
