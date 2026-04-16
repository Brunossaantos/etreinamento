<?php

class ConexaoGestor {
    private $servername = "localhost";
    private $username = "root";
    private $password = "UdlogT3c@";
    private $dbname = "gestor";
    private $conn;

    function conectar(){
        $this->conn = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname
        );

        if ($this->conn->connect_error) {
            die("Erro conexão gestor: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}