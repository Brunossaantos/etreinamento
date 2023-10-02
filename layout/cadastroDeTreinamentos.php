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
    <title>Adicionar novo treinamento</title>
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
include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$listaDeInstrutores = $daoInstrutor->gerarListaInstrurores();
$listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();



?>

<body>
    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>

    <div class="container mt-5">
        <h1>Cadastro de treinamento</h1>
        <div class="row">
            <!-- Coluna para a foto -->
            <div class="col-md-4">
                <img src="../imagens/treinamentos/default_treinamentos.jpg" alt="" class="img-fluid">
            </div>
            <!-- Coluna para o formulário -->
            <div class="col-md-8">
                <form action="../src/actions/adicionarTreinamento.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="idTreinamento" id="idTreinamento" value="">
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" name="descricao" id="descricao" value="">
                    </div>
                    <div class="mb-3">
                        <label for="conteudo" class="form-label">Conteúdo do treinamento</label>
                        <textarea class="form-control" name="conteudo" id="conteudo" rows="10"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cargaHoraria" class="form-label">Carga Horária</label>
                        <input type="time" name="cargaHoraria" id="cargaHoraria" class="form-control" value="">
                    </div>
                    <div class="mb-3">
                        <label for="material" class="form-label">Material do treinamento</label>
                        <input type="file" class="form-control" name="material" id="material" value="">
                    </div>
                    <div class="mb-3">
                        <label for="instrutor" class="form-label">Instrutor</label>
                        <select name="instrutor" id="instrutor" class="form-control">
                            <?php foreach ($listaDeInstrutores as $instrutor) {
                                if ($instrutor->getStatusInstrutor() == 1) { ?>
                                    <option value="<?php echo $instrutor->getIdInstrutor() ?>">
                                        <?php echo $instrutor->getNomeInstrutor() ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="local" class="form-label">Local do treinamento</label>
                        <input type="text" class="form-control" name="local" id="local" value="" placeholder="Local do treinamento">
                    </div>
                    <div class="mb-3">
                        <label for="data" class="form-label">Data</label>
                        <input type="date" class="form-control" name="data" id="data" value="">
                    </div>
                    <div class="mb-3">
                        <label for="departamento" class="form-label">Departamento:</label>
                        <select name="departamento" id="departamento" class="form-control">
                            <?php foreach ($listaDeDepartamentos as $departamento) {
                                if ($departamento->getStatusDepartamento() == 1) { ?>
                                    <option value="<?php echo $departamento->getIdDepartamento() ?>">
                                        <?php echo $departamento->getNomeDepartamento() ?>
                                    </option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="matricula" class="form-label">Status do cadastro:</label>

                        <label>
                            <input type="radio" class="form-contro" name="status" id="status" value="1" checked>Ativo
                        </label>
                        <label>
                            <input type="radio" class="form-contro" name="status" id="status" value="0"> Inativo
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar novo treinamento</button>
                    <a href="gerenciarTreinamento.php" class="btn btn-secondary">Cancelar</a>
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