<?php
use FTP\Connection;

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoUsuario.php');

$idUsuario = $_GET['idUsuario'];
$nome = $_GET['nome'];
$email = $_GET['email'];
$login = $_GET['login'];
$status = $_GET['status'];

$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

if($daoUsuario->atuaizarUsuario($idUsuario, $login, $nome, $email, $status)){
    header("Location: ../../layout/gerenciarConta.php");
    exit();
} else {
    header("Location: ../../layout/gerenciarConta.php");
    exit();
}

?>