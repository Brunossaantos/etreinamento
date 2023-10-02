<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION["user_id"])) {
    // O usuário não está logado, redirecione para a página de login
    header("Location: ../index.php");
    exit();
}

// O usuário está logado, continue exibindo o conteúdo da página protegida
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar novo usuário</title>

    <!-- Inclua o link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../estilo/estilo.css"> -->
    <style>
        .thumbColad,
        img {
            height: 100px;
            width: auto;
        }
    </style>
</head>
<?php

include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoUsuario.php');

$idUsuario = $_SESSION["user_id"];
$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

$usuario = $daoUsuario->consultarUsuario($idUsuario);

?>

<body>
    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>
    <div class="container mt-5">
        <h1>Cadastrar novo usuário</h1>
        <div class="row">

            <!-- Coluna para o formulário -->
            <div class="col-md-8">
                <form action="../src/actions/cadastrarUsuario.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="nome" id="nome"
                            value="">
                    </div>
                    <div class="mb-3">
                        <label for="matricula" class="form-label">email:</label>
                        <input type="text" class="form-control" name="email" id="email"
                            value="">
                    </div>
                    <div class="mb-3">
                        <label for="cargo" class="form-label">Login:</label>
                        <input type="text" class="form-control" name="login" id="login"
                            value="">
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" name="senha" id="senha">
                    </div>
                    <div>
                        <label for="status" class="form-label">Status do usuário</label>
                        <input type="radio" id="status" name="status" value="1" checked>Ativo
                            <input type="radio" id="status" name="status" value="0">Inativo
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="../index2.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>

    <!-- Inclua os scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
</body>

<script>
    // Use JavaScript para carregar o conteúdo do menu.html no elemento com o ID "menu-container"
    fetch('menusuperior.php')
        .then(response => response.text())
        .then(menuHTML => {
            document.getElementById('menu-container').innerHTML = menuHTML;
        })
        .catch(error => {
            console.error('Erro ao carregar o menu:', error);
        });
</script>

</html>