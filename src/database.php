<?php

class Database
{
    private $server_name = "localhost";
    private $username = "PII50461LA";
    private $password = "jkm_PII50461LA";
    private $db_name = "PII50461LA";

    public function __construct($server_name = null, $username = null, $password = null, $db_name = null)
    {
        $this->server_name = $server_name ?? $this->server_name;
        $this->username    = $username    ?? $this->username;
        $this->password    = $password    ?? $this->password;
        $this->db_name     = $db_name     ?? $this->db_name;
    }

    public function connect()
    {
        $conn = new mysqli($this->server_name, $this->username, $this->password, $this->db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
    
    public function disconnect($conn)
    {
        $conn->close();
    }

    public function query($conn, $sql)
    {
        $result = $conn->query($sql);
        if ($result === FALSE) {
            echo "Error: " . $conn->error;
            return null;
        }
        return $result;
    }
}

?>
