<?php
class Database
{

    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "contest";
    private $username = "root";
    private $password = "";
    public $conn;

    // get the database connection
    public function getConnection()
    {
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            echo "Connection Successfully";
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
