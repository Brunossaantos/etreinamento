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
    <title>Alterar Instrutor</title>
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
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

$idInstrutor = $_GET['idInstrutor'];
$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$instrutor = $daoInstrutor->selecionarInstrutor($idInstrutor);
$listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();

function nomeDoDepartamento($daoDepartamento, $idDepartamento)
{
    $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);

    if ($departamento != null) {
        return $departamento->getNomeDepartamento();
    } else {
        return "Departamento não cadastrado";
    }
}

?>

<body>
    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>

    <div class="container mt-5">
        <h1>Alterar Instrutor</h1>
        <div class="row">
            <!-- Coluna para a foto -->
            <div class="col-md-4">
                <img src="../imagens/treinamentos/default_treinamentos.jpg" alt="" class="img-fluid">
            </div>
            <!-- Coluna para o formulário -->
            <div class="col-md-8">
                <form action="../src/actions/atualizarInstrutor.php" method="get" enctype="multipart/form-data">
                    <input type="hidden" name="idInstrutor" id="idInstrutor"
                        value="<?php echo $instrutor->getIdInstrutor() ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="nome"
                            value="<?php echo $instrutor->getNomeInstrutor() ?>">
                    </div>
                    <div class="mb-3">
                        <label for="departamento" class="form-label">Departamento</label>
                        <select name="departamento" id="departamento" class="form-control">
                            <option value="<?php echo $instrutor->getIdInstrutor() ?>" select>
                                <?php echo nomeDoDepartamento($daoDepartamento, $instrutor->getDepartamentoInstrutor()) ?>
                            </option>
                            <?php foreach ($listaDeDepartamentos as $departamento) { ?>
                                <option value="<?php echo $departamento->getIdDepartamento() ?>">
                                    <?php echo $departamento->getNomeDepartamento() ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <!-- <input type="text" class="form-control" name="status" id="status" value=""> -->
                        <input type="radio" name="status" id="status" value="1" <?php if ($instrutor->getStatusInstrutor() == 1)
                            echo "checked" ?>> Ativo
                            <input type="radio" name="status" id="status" value="0" <?php if ($instrutor->getStatusInstrutor() == 0)
                            echo "checked" ?>> Inativo
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="gerenciarInstrutores.php" class="btn btn-secondary">Cancelar</a>
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
        fetch('menusuperior.html')
            .then(response => response.text())
            .then(menuHTML => {
                document.getElementById('menu-container').innerHTML = menuHTML;
            })
            .catch(error => {
                console.error('Erro ao carregar o menu:', error);
            });
    </script>

    </html>