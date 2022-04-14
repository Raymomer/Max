<?php
class Detial
{

    // database connection and table name
    private $conn;
    private $table_name = "contest";

    // object properties
    public $no;
    public $date;
    public $type;
    public $time;
    public $away_team;
    public $home_team;
    public $lose;
    public $win;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function read()
    {
        $sql = "SELECT * FROM  $this->table_name ";
        // $this->conn->query($sql);


        // $stmt  = mysqli_query($this->conn, $sql);


        $json = array();
        foreach (mysqli_fetch_all(mysqli_query($this->conn, $sql)) as $row) {
    
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


        return $json ;
    }
}
