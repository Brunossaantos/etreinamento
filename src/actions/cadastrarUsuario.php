<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ .'/../DAO/DaoUsuario.php');

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $status = $_POST['status'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $conexao = new Conexao();
    $daoUsuario = new DaoUsuario($conexao->conectar());

    if($daoUsuario->adicionarUsuario($login, $nome, $email, $senha)){
        header("Location: ../../layout/index2.php");
        exit();
    } else {
        header("Location: ../../layout/index2.php");
        exit();
    }

}

?>