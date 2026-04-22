<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos usuários
include(__DIR__ . '/../DAO/DaoUsuario.php');

// Dados recebidos via GET
// ALERTA: Uso de GET para atualização e sem validação/sanitização
$idUsuario = $_GET['idUsuario'];
$nome = $_GET['nome'];
$email = $_GET['email'];
$login = $_GET['login'];
$status = $_GET['status'];

// Instancia conexão e DAO
$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

// Atualiza os dados do usuário
$daoUsuario->atuaizarUsuario($idUsuario, $login, $nome, $email, $status);

// Redireciona após operação
header("Location: ../../layout/gerenciarConta.php");
exit();