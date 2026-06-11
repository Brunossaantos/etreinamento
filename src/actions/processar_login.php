<?php

session_start();
include(__DIR__ . '/../database/conexao.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = $_POST["login"];
    $senha = $_POST["senha"];

    $conexao = new Conexao();
    $conn = $conexao->conectar();

    $query = "
        SELECT 
            ID_USUARIO,
            LOGIN,
            NOME,
            SENHA_HASH,
            primeiro_acesso,
            PERFIL
        FROM usuarios 
        WHERE LOGIN = ?
        AND STATUS_USUARIO = 1
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

        if (password_verify($senha, $row["SENHA_HASH"])) {

            $_SESSION["user_id"] = $row["ID_USUARIO"];
            $_SESSION["nome"] = $row["NOME"];
            $_SESSION["perfil"] = (int) $row["PERFIL"];

            if ($row["primeiro_acesso"] == 1) {
                header("Location: ../../layout/trocar_senha.php?id=" . $row["ID_USUARIO"]);
                exit();
            }

            header("Location: ../../index2.php");
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Nome de usuário não encontrado ou inativo.";
    }
}