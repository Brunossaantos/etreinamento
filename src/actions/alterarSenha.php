<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelas operações de usuário
include(__DIR__ . '/../DAO/DaoUsuario.php');

// Instancia conexão e DAO
$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

// Captura o ID do usuário
// ALERTA: Sem validação/sanitização
$idUsuario = $_POST['idUsuario'];

// Busca dados do usuário (necessário para validar senha atual)
$usuario = $daoUsuario->consultarUsuario($idUsuario);

// Garante que a requisição seja POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Dados do formulário
    $senha_atual = $_POST["senha_atual"];
    $nova_senha = $_POST["nova_senha"];
    $confirmar_senha = $_POST["confirmar_senha"];

    // Validação: nova senha deve coincidir com confirmação
    if ($nova_senha !== $confirmar_senha) {
        echo "As senhas não coincidem.";
        exit;
    }

    // Recupera hash da senha atual
    $senha_atual_hash = $usuario->getSenha();

    // Valida senha atual
    if (password_verify($senha_atual, $senha_atual_hash)) {

        // Gera novo hash da senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualiza senha no banco
        if ($daoUsuario->alterarSenha($idUsuario, $nova_senha_hash)) {
            echo "Senha alterada com sucesso.";
        }

        // Redireciona após sucesso
        header("Location: ../../layout/gerenciarConta.php");
        exit();
    } else {

        // Senha atual inválida
        echo "Senha atual incorreta.";

        // ALERTA: echo antes do header pode causar erro de redirecionamento
        header("Location: ../../layout/gerenciarConta.php");
        exit();
    }
}