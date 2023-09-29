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
    <title>Gerenciador de Empresas cadastradas</title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
    <!-- Inclua o link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../estilo/estilo.css"> -->
    <style>
        .logotipo {
            height: 50px;
        }
    </style>
</head>
<?php
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/Util/Util.php');

if(isset($_GET['pesquisaEmpresa'])){
    $pesquisaEmpresa = $_GET['pesquisaEmpresa'];
}

$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());

if(empty($pesquisaEmpresa)){
    $listaDeEmpresas = $daoEmpresa->gerarListaEmpresas();
} else {
    $listaDeEmpresas = $daoEmpresa->pesquisarEmpresas($pesquisaEmpresa);
}

$util = new Util();

function statusDaEmpresa($statuDaEmpresa)
{
    if ($statuDaEmpresa == 1) {
        return "Empresa ativa";
    } else {
        return "Empresa inativa";
    }
}

?>

<body>

    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>    
    <div class="container mt-5">
    <h1>Gerenciador de empresas cadastradas</h1>
        <!-- Formulário de Pesquisa -->
        <form method="GET">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Pesquisar empresas cadastradas" name="pesquisaEmpresa" id="pesquisaEmpresa">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                </div>
                <div class="col-md-2">
                    <a href="cadastrarEmpresa.php" class="btn btn-success">Cadastrar empresa</a>
                </div>
            </div>
        </form>

        <!-- Tabela de Treinamentos -->
        <table class="table">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th></th>
                    <th>Status da empresa</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de uma linha na tabela (pode adicionar mais linhas dinamicamente) -->
                <?php foreach ($listaDeEmpresas as $empresa) { ?>
                    <tr>
                        <td>
                            <?php echo $empresa->getNomeEmpresa() ?>
                        </td>
                        <td><img src="<?php echo $util->montarCaminhoLogotipo(null, $empresa->getIdEmpresa()) ?>"
                                alt="<?php echo $empresa->getNomeEmpresa() ?>" class="logotipo"></td>
                        <td>
                            <?php echo statusDaEmpresa($empresa->getStatusEmpresa()) ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-danger" value=""
                                    onclick="excluirTreinamento('<?php echo $empresa->getIdEmpresa() ?>')">Excluir
                                    empresa</button>
                                <button class="btn btn-warning" value=""
                                    onclick="alterarTreinamento('<?php echo $empresa->getIdEmpresa() ?>')">Atualizar
                                    empresa</button>
                                <?php if ($empresa->getStatusEmpresa() == 1) { ?>
                                    <button class="btn btn-success" value=""
                                        onclick="iniciarTreinamento('<?php echo $empresa->getIdEmpresa() ?>')">Status da
                                        empresa</button>
                                <?php } else { ?>
                                    <button class="btn btn-secondary" value=""
                                        onclick="iniciarTreinamento('<?php echo $empresa->getIdEmpresa() ?>')">Status da
                                        empresa</button>
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
        function excluirTreinamento(idEmpresa) {
            window.location.href = '../src/actions/excluirEmpresa.php?idEmpresa=' + idEmpresa
        }

        function alterarTreinamento(idEmpresa) {
            window.location.href = '../layout/atualizarEmpresa.php?idEmpresa=' + idEmpresa
        }

        function iniciarTreinamento(idEmpresa) {
            window.location.href = '../src/actions/alterarStatusEmpresa.php?idEmpresa=' + idEmpresa
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