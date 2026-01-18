<?php

class Database
{
    private $server_name;
    private $username;
    private $password;
    private $db_name;

    public function __construct($server_name, $username, $password, $db_name)
    {
        $this->server_name = $server_name;
        $this->username = $username;
        $this->password = $password;
        $this->db_name = $db_name;
    }

    public function connect()
    {
        $conn = new mysqli($this->server_name, $this->username, $this->password, $this->db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
}

?>
