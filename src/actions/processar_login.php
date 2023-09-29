<?php
session_start();

include(__DIR__ . '/../database/conexao.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["login"];
    $senha = $_POST["senha"];

    $conexao = new Conexao();
    $conn = $conexao->conectar();

    // Consulta SQL para verificar as credenciais
    $query = "SELECT ID_USUARIO, LOGIN, SENHA_HASH FROM USUARIOS WHERE LOGIN = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row["SENHA_HASH"])) { // Use "SENHA_HASH" para verificar a senha
            // Autenticação bem-sucedida
            $_SESSION["user_id"] = $row["ID_USUARIO"];
            header("Location: ../../index2.php");
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Nome de usuário não encontrado.";
    }
}
?>
