<?php

class Database
{
    private $server_name = "localhost";
    private $username = "PII50461LA";
    private $password = "jkm_PII50461LA";
    private $db_name = "PII50461LA";
    private ?mysqli $conn = null;

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
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        $this->conn = $conn;
        return $conn;
    }

    public function disconnect()
    {
        $this->conn->close();
    }

    public function query(string $sql, array $params = [])
    {
        $conn = $this->connect();

        if ($params) {
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
            $stmt->execute();

            $result = $stmt->get_result();
            $stmt->close();
        } else {
            $result = $conn->query($sql);
            if ($result === false) {
                throw new Exception("Query failed: " . $conn->error);
            }
        }

        return $result;
    }
}
