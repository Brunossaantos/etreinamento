<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoUsuario.php');

$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

$idUsuario = $_POST['idUsuario'];

$usuario = $daoUsuario->consultarUsuario($idUsuario);

//echo password_hash($usuario->getSenha(), PASSWORD_DEFAULT);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $senha_atual = $_POST["senha_atual"];
    $nova_senha = $_POST["nova_senha"];
    $confirmar_senha = $_POST["confirmar_senha"];

    if ($nova_senha !== $confirmar_senha) {
        echo "As senhas não coincidem. Tente novamente.";
        exit;
    }

    // Verifique se a senha atual é válida (substitua 'senha_atual_hash' pelo hash da senha atual no banco de dados)
    $senha_atual_hash = $usuario->getSenha();

    if (password_verify($senha_atual, $senha_atual_hash)) {
        // A senha atual é válida, então podemos criar um novo hash para a nova senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        if ($daoUsuario->alterarSenha($idUsuario, $nova_senha_hash)) {
            echo "Senha alterada com sucesso.<br>";
        }

        header("Location: ../../layout/gerenciarConta.php");
        exit();
    } else {
        echo "Senha atual incorreta. Tente novamente.";
        header("Location: ../../layout/gerenciarConta.php");
        exit();
    }
}
?>