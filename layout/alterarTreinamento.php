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
    <title>Alterar treinamento</title>
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

$idTreinamento = $_GET['idTreinamento'];
$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$listaDeInstrutores = $daoInstrutor->gerarListaInstrurores();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
$listaDepartamentos = $daoDepartamento->gerarListaDepartamentos();

function recuperarNomeInstrutor($daoInstrutor,$idInstrutor){
    $instrutor = $daoInstrutor->selecionarInstrutor($idInstrutor);
    return $instrutor->getNomeInstrutor();
}

function recuperarNomeDepartamento($daoDepartamento, $idDepartamento){
    $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);
    return $departamento->getNomeDepartamento();
}

?>
<body>
<div id="menu-container">
  <!-- O menu será carregado aqui -->
</div>

    <div class="container mt-5">
        <h1>Alterar treinamento</h1>
        <div class="row">
            <!-- Coluna para a foto -->
            <div class="col-md-4">
                <img src="../imagens/treinamentos/default_treinamentos.jpg" alt=""
                    class="img-fluid">
            </div>
            <!-- Coluna para o formulário -->
            <div class="col-md-8">
                <form action="../src/actions/alterarTreinamento.php" method="get" enctype="multipart/form-data">
                
                    <input type="hidden" name="idTreinamento" id="idTreinamento" value="<?php echo $treinamento->getIdTreinamento()?>">                   
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" name="descricao" id="descricao"
                            value="<?php echo $treinamento->getDescricaoTreinamento()?>">
                    </div>
                    <div class="mb-3">
                        <label for="conteudo" class="form-label">Conteúdo do treinamento</label>
                        <textarea name="conteudo" id="conteudo" cols="30" rows="10" class="form-control"><?php echo $treinamento->getConteudoTreinamento()?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cargaHoraria" class="form-label">Carga horária do treinamento</label>
                        <input type="time" class="form-control" name="cargaHoraria" id="cargaHoraria" value="<?php echo $treinamento->getCargaHoraria()?>">
                    </div>
                    <div class="mb-3">
                        <label for="instrutor" class="form-label">Instrutor</label>
                        <select class="form-control" name="instrutor" id="instrutor">
                            <option selected value="<?php echo $treinamento->getInstrutor()?>"><?php echo recuperarNomeInstrutor($daoInstrutor, $treinamento->getInstrutor()) ?></option>
                            <?php  foreach($listaDeInstrutores as $instrutor){?>
                                <option value="<?php echo $instrutor->getIdInstrutor()?>"><?php echo $instrutor->getNomeInstrutor()?></option>
                            <?php }?>
                        </select>
                        <div class="mb-3">
                            <label for="local" class="form-label">Local do treinamento</label>
                            <input type="text" class="form-control" name="local" id="local" value="<?php echo $treinamento->getLocalTreinamento()?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="data" class="form-label">Data</label>
                        <input type="date" class="form-control" name="data" id="data"
                            value="<?php echo $treinamento->getDataTreinamento()?>">
                    </div>
                    <div class="mb-3">
                        <label for="departamento" class="form-label">Departamento:</label>
                        <select class="form-control" name="departamento" id="departamento">
                            <option selected value="<?php echo $treinamento->getDepartamento()?>"><?php echo recuperarNomeDepartamento($daoDepartamento, $treinamento->getDepartamento())?></option>
                            <?php foreach($listaDepartamentos as $departamento){?>
                                <option value="<?php echo $departamento->getIdDepartamento()?>"><?php echo $departamento->getNomeDepartamento()?></option>
                            <?php }?>                        
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <input type="radio" id="status" name="status" value="1" <?php if ($treinamento->getStatusTreinamento() == 1) echo "checked"; ?>>Ativo
                        <input type="radio" id="status" name="status" value="0" <?php if ($treinamento->getStatusTreinamento() == 0) echo "checked"; ?>>Inativo
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
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