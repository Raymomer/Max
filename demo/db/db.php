<?php


class DB
{
    protected $conn;
    protected $query;
    protected $show_errors = TRUE;
    public $show_log = FALSE;
    protected $query_closed = TRUE;

    public function __construct($servername = "localhost", $username = "root", $password = "", $dbname = "contest", $charset = 'utf8', $show_log = 'FALSE')
    {



        //  check DB connect
        $this->conn = new mysqli($servername, $username, $password);
        if ($this->conn->connect_error) {
            $this->error("Connection Table failed: " . $this->conn->connect_error);
        }


        $sql = "CREATE DATABASE $dbname";
        $this->conn->query($sql);

        //  check DB connect
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            $this->error("Connection Table failed: " . $this->conn->connect_error);
        }
        if ($this->show_log) {
            echo ("Start database's connection <br>");
        }

        $this->conn->set_charset($charset);

        // check $table exists
        $sql = "SELECT 1 from $dbname LIMIT 1";
        if ($this->conn->query($sql) === FALSE) {

            // sql to create table
            $sql =
                "CREATE TABLE $dbname (
    
                    no   CHAR(4)  NOT NULL,
                    date  DATE  NOT NULL,
                    type  CHAR (4) NOT NULL,
                    time  VARCHAR (12) NOT NULL,
                    away_team CHAR (4) NOT NULL,
                    home_team CHAR (4) NOT NULL,
                    lose  FLOAT  NOT NULL,
                    win   FLOAT  NOT NULL,
                    primary key (no, date)
            
            )
           ";


            if ($this->conn->query($sql) !== TRUE) {
                $this->error("Create TABLE failed: " . $this->conn->connect_error);
            }
            if ($this->show_log) {
                echo "Create table successfully";
            }

            return;
        }
    }

    public function insertDB($dbname, $element, $data)
    {
        $sql = "INSERT INTO $dbname $element VALUES $data;";

        // echo ("$sql<br>");


        if ($this->conn->query($sql) === FALSE) {
            if (preg_match('/PRIMARY/', $this->conn->error, $log)) {

                $this->error("InsertDB failed: Data already exists");
                return $this->conn->error;
            }

            $this->error("InsertDB failed: " . $this->conn->error);
            return $this->conn->error;
        }


        return;
    }

    public function checkDBData($dbname, $date)
    {
        $sql = "SELECT * FROM  $dbname WHERE date = '$date' ;";
        if ($this->conn->query($sql) === FALSE) {

            $this->error("function CheckDBData failed: " . $this->conn->connect_error);
        }


        $result = mysqli_query($this->conn, $sql);
        if ($result->num_rows == 0) {
            return "Start catch data";
        }

        return;
    }

    public function selectDBData($dbname, $date)
    {

        $sql = "SELECT * FROM  $dbname WHERE date = '$date' ;";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteDB($dbname)
    {

        $sql = "DROP DATABASE $dbname";
        if ($this->conn->query($sql) === FALSE) {
            $this->error("InsertDB failed: " . $this->conn->error);
        }
    }

    public function error($error)
    {
        if ($this->show_errors) {
            exit($error);
        }
    }
}
