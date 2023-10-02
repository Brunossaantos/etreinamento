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
    <title>Gerenciador de Treinamentos</title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
    <!-- Inclua o link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../estilo/estilo.css"> -->
</head>
<?php   
include (__DIR__.'/../src/database/conexao.php');
include (__DIR__.'/../src/DAO/DaoTreinamento.php');
include (__DIR__.'/../src/DAO/DaoInstrutor.php');
include (__DIR__.'/../src/Util/Util.php');

if(isset($_GET['pesquisaTreinamento'])){
    $pesquisaTreinamento = $_GET['pesquisaTreinamento'];
}

$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$daoIntrutor = new DaoInstrutor($conexao->conectar());
$util = new Util();

if(empty($pesquisaTreinamento)){
    $listaTreinamento = $daoTreinamento->gerarListaTreinamentos();
} else {
    $listaTreinamento = $daoTreinamento->pesquisarTreinamento($pesquisaTreinamento);
}

function nomeInstrutor($daoIntrutor, $idInstrutor){
    if($daoIntrutor != null){
        $instrutor = $daoIntrutor->selecionarInstrutor($idInstrutor);
        return $instrutor->getNomeInstrutor();
    } else {
        return null;
    }    
}

function verificarDataTreinamento($dataTreinamento){
    $dataAtual = new DateTime();
    $dataAtual->setTime(0, 0, 0);
    $dataProgramada = new DateTime($dataTreinamento); 
   
    if($dataProgramada == $dataAtual){
        return true;
    } else {
        return false;
    }    
}


?>
<body>
<div id="menu-container">
  <!-- O menu será carregado aqui -->
</div>
    <div class="container mt-5">
    <h1>Gerenciador de treinamentos</h1>
        <!-- Formulário de Pesquisa -->
        <form method="GET">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Pesquisar treinamentos" name="pesquisaTreinamento" id="pesquisaTreinamento">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                </div>
                <div class="col-md-2">
                    <a href="cadastroDeTreinamentos.php" class="btn btn-success"> Adicionar treinamento </a>                    
                </div>
            </div>
        </form>

        <!-- Tabela de Treinamentos -->
        <table class="table">
            <thead>
                <tr>
                    <th>Data do Treinamento</th>
                    <th>Descrição do Treinamento</th>
                    <th>Instrutor do Treinamento</th>
                    <th>Carga Horária</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de uma linha na tabela (pode adicionar mais linhas dinamicamente) -->
                <?php foreach($listaTreinamento as $treinamento){?>
                <tr>
                    <td><?php echo $util->formatarData($treinamento->getDataTreinamento())?></td>
                    <td><a href="visualizarTreinamento.php?idTreinamento=<?php echo $treinamento->getIdTreinamento()?>"><?php echo $treinamento->getDescricaoTreinamento();?></a></td>
                    <td><?php echo nomeInstrutor($daoIntrutor, $treinamento->getInstrutor());?></td>
                    <td><?php echo $treinamento->getCargaHoraria()?></td>
                    <td>
                    <div class="btn-group">
                        <button class="btn btn-danger" value="" onclick = "excluirTreinamento('<?php echo $treinamento->getIdTreinamento()?>')">Cancelar Treinamento</button>
                        <button class="btn btn-warning" value="" onclick = "alterarTreinamento('<?php echo $treinamento->getIdTreinamento()?>')">Alterar Treinamento</button>
                        <?php if(verificarDataTreinamento($treinamento->getDataTreinamento())){ ?>
                            <button class="btn btn-success" value="" onclick = "iniciarTreinamento('<?php echo $treinamento->getIdTreinamento()?>')">Iniciar Treinamento</button>
                        <?php } else {?>
                            <button class="btn btn-secondary" value="" onclick = "alertaTreinamento()">Treinamento encerrado</button>
                        <?php }?>
                        <button class="btn btn-primary" value="" onclick = "gerarListaPresenca('<?php echo $treinamento->getIdTreinamento()?>')">Gerar lista de presença</button>
                    </div>
                    </td>
                </tr>
                <?php }?>
                <!-- Outras linhas da tabela aqui... -->
            </tbody>
        </table>
    </div>
    <script>
        function excluirTreinamento(idTreinamento){
            window.location.href = '../src/actions/excluirTreinamento.php?idTreinamento=' + idTreinamento 
        }
        
        function alterarTreinamento(idTreinamento){
            window.location.href = '../layout/alterarTreinamento.php?idTreinamento=' +idTreinamento
        }

        function iniciarTreinamento(idTreinamento){
            window.location.href = '../layout/listaDePresenca.php?idTreinamento=' +idTreinamento
        }

        function gerarListaPresenca(idTreinamento){
            window.location.href = '../layout/gerarListaDePresenca.php?idTreinamento= ' +idTreinamento
        }
        
        function alertaTreinamento(){
            alert("Esse treinamento ja foi encerrado.");
        }
    </script>
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
