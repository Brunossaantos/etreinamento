<?php

// Inicia sessão para controle de login
session_start();

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// Garante que a requisição seja POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Dados do formulário
    // ALERTA: Sem validação/sanitização
    $usuario = $_POST["login"];
    $senha = $_POST["senha"];

    // Instancia conexão
    $conexao = new Conexao();
    $conn = $conexao->conectar();

    // Busca usuário pelo login
    $query = "SELECT ID_USUARIO, LOGIN, NOME, SENHA_HASH FROM USUARIOS WHERE LOGIN = ?";

    // Uso de prepared statement (segurança)
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Valida se usuário existe
    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

        // Verifica senha
        if (password_verify($senha, $row["SENHA_HASH"])) {

            // Cria sessão do usuário
            $_SESSION["user_id"] = $row["ID_USUARIO"];
            $_SESSION["nome"] = $row["NOME"];

            // Redireciona após login
            header("Location: ../../index2.php");
            exit();
        } else {

            echo "Senha incorreta.";
        }
    } else {

        echo "Nome de usuário não encontrado.";
    }
}