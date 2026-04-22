<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoUsuario.php');

$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $idUsuario = $_POST["idUsuario"];
    $senha_atual = $_POST["senha_atual"];
    $nova_senha = $_POST["nova_senha"];
    $confirmar_senha = $_POST["confirmar_senha"];

    // 🔐 valida confirmação
    if ($nova_senha !== $confirmar_senha) {
        header("Location: ../../layout/gerenciarConta.php?erro=senhas_diferentes");
        exit();
    }

    // 🔎 busca usuário APENAS aqui
    $usuario = $daoUsuario->consultarUsuario($idUsuario);

    if (!$usuario) {
        header("Location: ../../layout/gerenciarConta.php?erro=usuario_nao_encontrado");
        exit();
    }

    // 🔐 valida senha atual
    if (!password_verify($senha_atual, $usuario->getSenha())) {
        header("Location: ../../layout/gerenciarConta.php?erro=senha_incorreta");
        exit();
    }

    // 🔒 gera nova senha
    $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    // 🔄 atualiza senha + libera primeiro acesso
    if ($daoUsuario->alterarSenha($idUsuario, $nova_senha_hash)) {
        header("Location: ../../layout/gerenciarConta.php?sucesso=senha_alterada");
        exit();
    } else {
        header("Location: ../../layout/gerenciarConta.php?erro=falha_update");
        exit();
    }
}
