<?php

class Usuario{
    private $idUsuario;
    private $loginUsuario;
    private $senha;
    private $nome;
    private $email;
    private $statusUsuario;

    function __construct($idUsuario, $loginUsuario, $senha, $nome, $email, $statusUsuario){
        $this->setIdUsuario($idUsuario);
        $this->setLogin($loginUsuario);
        $this->setSenha($senha);
        $this->setNome($nome);
        $this->setEmail($email);
        $this->setStatusUsuario($statusUsuario);
    }

    function setIdUsuario($idUsuario){
        $this->idUsuario = $idUsuario;
    }

    function setLogin($loginUsuario){
        $this->loginUsuario = $loginUsuario;
    }

    function setSenha($senha){
        $this->senha = $senha;
    }

    function setNome($nome){
        $this->nome = $nome;
    }

    function setEmail($email){
        $this->email=$email;
    }

    function setStatusUsuario($statusUsuario){
        $this->statusUsuario= $statusUsuario;
    }

    function getIdUsuario(){
        return $this->idUsuario;
    }

    function getLogin(){
        return $this->loginUsuario;
    }

    function getSenha(){
        return $this->senha;
    }

    function getNome(){
        return  $this->nome;
    }

    function getEmail(){
        return $this->email;
    }

    function getStatusUsuario(){
        return $this->statusUsuario;
    }

    function __toString(){
        return
        "<br> ID usuario: ".$this->getIdUsuario()
        ."<br> Login Usuario: ".$this->getLogin()
        ."<br> Senha: ".$this->getSenha()
        ."<br> Nome: ".$this->getNome()
        ."<br> Email: ".$this->getEmail()
        ."<br> Status do Usuário: ".$this->getStatusUsuario()."<br>";
    }
    
}

?>