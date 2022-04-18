<?php


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
