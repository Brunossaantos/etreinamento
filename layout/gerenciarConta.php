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
    <title>Gerenciar minha conta</title>

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
        <h1>Gerenciar minha conta</h1>
        <div class="row">

            <!-- Coluna para o formulário -->
            <div class="col-md-8">
                <form action="../src/actions/atualizarConta.php" enctype="multipart/form-data">
                    <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $usuario->getIdUsuario() ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" name="nome" id="nome"
                            value="<?php echo $usuario->getNome() ?>">
                    </div>
                    <div class="mb-3">
                        <label for="matricula" class="form-label">email:</label>
                        <input type="text" class="form-control" name="email" id="email"
                            value="<?php echo $usuario->getEmail() ?>">
                    </div>
                    <div class="mb-3">
                        <label for="cargo" class="form-label">Login:</label>
                        <input type="text" class="form-control" name="login" id="login"
                            value="<?php echo $usuario->getLogin() ?>">
                    </div>
                    <div class="mb-3">
                        <a href="#" class="btn btn-danger" onclick="openPopup()">Alterar senha</a>
                    </div>
                    <div>
                        <label for="status" class="form-label">Status do usuário</label>
                        <input type="radio" id="status" name="status" value="1" <?php if ($usuario->getStatusUsuario() == 1)
                            echo "checked" ?>>Ativo
                            <input type="radio" id="status" name="status" value="0" <?php if ($usuario->getStatusUsuario() == 0)
                            echo "checked" ?>>Inativo
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="../index2.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>

        <div id="myModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closePopup()">&times;</span>
                <!-- Coloque aqui o formulário de alteração de senha -->
                <form method="post" action="../src/actions/alterarSenha.php">
                    <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $usuario->getIdUsuario() ?>">
                <label for="senha_atual">Senha Atual:</label>
                <input type="password" name="senha_atual" required><br>

                <label for="nova_senha">Nova Senha:</label>
                <input type="password" name="nova_senha" required><br>

                <label for="confirmar_senha">Confirmar Nova Senha:</label>
                <input type="password" name="confirmar_senha" required><br>

                <input type="submit" value="Alterar Senha">
            </form>
        </div>
    </div>

    <!-- Inclua os scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
</body>

<script>
    function openPopup() {
        document.getElementById("myModal").style.display = "block";
    }

    function closePopup() {
        document.getElementById("myModal").style.display = "none";
    }
</script>

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