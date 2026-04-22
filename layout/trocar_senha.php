<?php
session_start();

// 🔒 proteção básica: precisa estar logado
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$idUsuario = $_SESSION["user_id"];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Trocar Senha</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 250px;
            box-shadow: 0px 0px 10px #ccc;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 8px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>

<div class="box">
    <h2>Alterar Senha</h2>

    <form method="POST" action="../src/actions/alterarSenha.php">

        <input type="hidden" name="idUsuario" value="<?= $idUsuario ?>">

        <input type="password" name="senha_atual" placeholder="Senha atual" required>

        <input type="password" name="nova_senha" placeholder="Nova senha" required>

        <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required>

        <button type="submit">Alterar Senha</button>

    </form>
</div>

</body>
</html>