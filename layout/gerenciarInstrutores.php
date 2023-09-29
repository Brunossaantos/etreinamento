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
    <title>Gerenciador de Instrutores</title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
    <!-- Inclua o link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../estilo/estilo.css"> -->
    <style>
        .thumbColad,
        img {
            height: 100px;
            width: auto;
            border-radius: 10px;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<?php

if(isset($_GET['pesquisaInstrutor'])){
    $pesquisaInstrutor = $_GET['pesquisaInstrutor'];
}

include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');

$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());

if(empty($pesquisaInstrutor)){
    $listaDeInstrutores = $daoInstrutor->gerarListaInstrurores();
} else {
    $listaDeInstrutores = $daoInstrutor->pesquisarInstrutores($pesquisaInstrutor);
}


function nomeDepartamento($daoDepartamento, $idDepartamento)
{
    $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);

    if ($departamento != null) {
        return $departamento->getNomeDepartamento();
    } else {
        return "Departamento não cadastrado";
    }
}

function statusInstrutor($statusInstrutor){
    if($statusInstrutor == 1){
        return "Ativo";
    } else {
        return "Inativo";
    }
}



?>

<body>
    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>    
    <div class="container mt-5">
    <h1>Gerenciador de instrutores</h1>
        <!-- Formulário de Pesquisa -->
        <form method="GET">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Pesquisar instrutor" name="pesquisaInstrutor" id="pesquisaInstrutor">
                </div>
                <div class="col-md-2 btn-group">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>                    
                </div>
                <div class="col-md-2">
                    <a href="cadastrarInstrutor.php" class="btn btn-success">Cadastrar Instrutor</a>
                </div>
            </div>
        </form>

        <!-- Tabela de Treinamentos -->
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Departamento</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de uma linha na tabela (pode adicionar mais linhas dinamicamente) -->
                <?php foreach ($listaDeInstrutores as $instrutor) { ?>
                    <tr>
                        <td>
                            <?php echo $instrutor->getNomeInstrutor(); ?>
                        </td>
                        <td>
                            <?php echo nomeDepartamento($daoDepartamento, $instrutor->getDepartamentoInstrutor()) ?>
                        </td>
                        <td>
                            <?php echo statusInstrutor($instrutor->getStatusInstrutor()) ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-danger" value=""
                                    onclick="redirecionarParaExcluirInstrutor('<?php echo $instrutor->getIdInstrutor(); ?>')">Excluir</button>
                                <button class="btn btn-warning" value=""
                                    onclick="redirecionarParaAtualizarColaborador('<?php echo $instrutor->getIdInstrutor(); ?>')">Atualizar</button>
                                <?php if ($instrutor->getStatusInstrutor() == 1) { ?>
                                    <button class="btn btn-success" value=""
                                        onclick="redirecionarParaAlterarStatusInstrutor('<?php echo $instrutor->getIdInstrutor(); ?>')">Status</button>
                                <?php } else { ?>
                                    <button class="btn btn-secondary" value=""
                                        onclick="redirecionarParaAlterarStatusInstrutor('<?php echo $instrutor->getIdInstrutor(); ?>')">Status</button>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                <!-- Outras linhas da tabela aqui... -->
            </tbody>
        </table>
    </div>
    <script>
        function redirecionarParaAtualizarColaborador(idInstrutor) {
            window.location.href = 'alterarInstrutor.php?idInstrutor=' + idInstrutor;
        }

        function redirecionarParaExcluirInstrutor(idInstrutor) {
            window.location.href = '../src/actions/excluirInstrutor.php?idInstrutor=' + idInstrutor;
        }

        function redirecionarParaAlterarStatusInstrutor(idInstrutor) {
            window.location.href = '../src/actions/alterarStatusInstrutor.php?idInstrutor=' + idInstrutor;
        }

    </script>
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