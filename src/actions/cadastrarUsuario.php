<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos usuários
include(__DIR__ . '/../DAO/DaoUsuario.php');

// Garante que a requisição seja POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Dados do formulário
    // ALERTA: Sem validação/sanitização
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $status = $_POST['status'];

    // Gera hash seguro da senha
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Instancia conexão e DAO
    $conexao = new Conexao();
    $daoUsuario = new DaoUsuario($conexao->conectar());

    // Adiciona novo usuário
    $daoUsuario->adicionarUsuario($login, $nome, $email, $senha);

    // Redireciona após operação
    header("Location: ../../index2.php?sucesso=usuario_cadastrado");
    exit();
}