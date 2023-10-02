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
    <title>Alterar Cadastro do Colaborador</title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
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
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/Util/Util.php');

$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$util = new Util();


?>

<body>
    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>

    <div class="container mt-5">
        <h1>Alterar Cadastro da empresa</h1>
        <div class="row">
            <!-- Coluna para a foto -->
            <div class="col-md-4">
                <img src="../imagens/logotipos/default_logotipo.png" alt="iamgem da empresa aqui " class="img-fluid">
            </div>
            <!-- Coluna para o formulário -->
            <div class="col-md-8">
                <form action="../src/actions/processarCadastroEmpresa.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="idEmpresa" value="">
                    <div class="mb-3">
                        <label for="foto" class="form-label">Logotipo da empresa:</label>
                        <input type="file" class="form-control" name="logo" id="logo">
                    </div>
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da empresa</label>
                        <input type="text" class="form-control" name="nome" id="nome" value="">
                    </div>
                    <div class="mb-3">
                        <label for="matricula" class="form-label">Status do cadastro:</label>

                        <label>
                            <input type="radio" class="form-contro" name="status" id="status" value="1">Ativo
                        </label>
                        <label>
                            <input type="radio" class="form-contro" name="status" id="status" value="0"> Inativo
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="gerenciarEmpresas.php" class="btn btn-secondary">Cancelar</a>
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