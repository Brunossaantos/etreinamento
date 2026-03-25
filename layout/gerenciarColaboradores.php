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
    <title>Gerenciador de colaboradores</title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
    <!-- Inclua o link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <link href="../src/js/DataTables/datatables.css" rel="stylesheet">
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
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoColaborador.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/Util/util.php');

$conexao = new Conexao();
$daoColaborador = new DaoColaborador($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$util = new Util();

//$listaDeColaboradores = $daoColaborador->gerarListaColaboradores();
if(isset($_GET['pesquisaColab'])){
    $pesquisaColab = $_GET['pesquisaColab'];
}

// Carregue todos os colaboradores se não houver pesquisa
if (empty($pesquisaColab)) {
    $listaDeColaboradores = $daoColaborador->gerarListaColaboradores();
} else {
    // Se houver pesquisa, filtre os colaboradores
    $listaDeColaboradores = $daoColaborador->pesquisarColaborador($pesquisaColab);
}

function nomeDepartamento($daoDepartamento, $idDepartamento)
{
    $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);

    if ($departamento != null) {
        $nomeDepartamento = $departamento->getNomeDepartamento();
        return $nomeDepartamento;
    } else {
        return "Departamento inexistente";
    }

}

function nomeDaEmpresa($daoEmpresa, $idEmpresa)
{

    $empresa = $daoEmpresa->selecionarEmpresa($idEmpresa);
    if ($empresa != null) {
        $nomeEmpresa = $empresa->getNomeEmpresa();
        return $nomeEmpresa;
    } else {
        return "Empresa não cadastrada";
    }
}

?>

<body>
    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>
    <div class="container mt-5">
        <h1>Gerenciador de colaboradores</h1>
        <!-- Formulário de Pesquisa -->
        <form method="GET">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Pesquisar colaboradores" name="pesquisaColab" id="pesquisaColab">
                </div>
                <div class="col-md-2 btn-group">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>                    
                </div>
                <div class="col-md-2">
                <a href="cadastroColaborador.php" class="btn btn-success">Cadastrar colaborador</a>
                </div>
            </div>
        </form>

        <!-- Tabela de Treinamentos -->
        <table class="table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Empresa</th>
                    <th>Cargo</th>
                    <th>Departamento</th>
                    <th>Matricula</th>
                    <th>Crachá</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de uma linha na tabela (pode adicionar mais linhas dinamicamente) -->
                <?php foreach ($listaDeColaboradores as $colaborador) { ?>
                    <tr>
                        <td><img src="<?php echo $util->montarCaminhoFoto("../imagens/colaboradores/", $colaborador->getMatriculadoColaborador()) ?>"
                                alt="<?php echo $colaborador->getNomeColaborador() ?>" class="thumbColad"></td>
                        <td>
                            <?php echo $colaborador->getNomeColaborador() ?>
                        </td>
                        <td>
                            <?php echo nomeDaEmpresa($daoEmpresa, $colaborador->getIdEmpresaColaborador()) ?>
                        </td>
                        <td>
                            <?php echo $colaborador->getCargo() ?>
                        </td>
                        <td>
                            <?php echo nomeDepartamento($daoDepartamento, $colaborador->getDepartamentoColaborador()) ?>
                        </td>
                        <td>
                            <?php echo $colaborador->getMatriculadoColaborador() ?>
                        </td>
                        <td>
                            <?php echo $colaborador->getCrachaColaborador() ?>
                        </td>

                        <td>
                            <div class="btn-group">
                                <button class="btn btn-danger" value=""
                                    onclick="redirecionarParaExcluirColaborador('<?php echo $colaborador->getIdColaborador(); ?>')">Excluir</button>
                                <button class="btn btn-warning" value=""
                                    onclick="redirecionarParaAtualizarColaborador('<?php echo $colaborador->getIdColaborador(); ?>')">Atualizar</button>
                                <?php
                                if ($colaborador->getStatusColaborador() == 1) {
                                    ?>
                                    <button class="btn btn-success" value=""
                                        onclick="redirecionarParaAlterarStatusColaborador('<?php echo $colaborador->getIdColaborador(); ?>')">Status</button>
                                <?php } else { ?>
                                    <button class="btn btn-secondary" value=""
                                        onclick="redirecionarParaAlterarStatusColaborador('<?php echo $colaborador->getIdColaborador(); ?>')">Status</button>
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
        function redirecionarParaAtualizarColaborador(idColaborador) {
            window.location.href = 'atualizarColaborador.php?idColaborador=' + idColaborador;
        }

        function redirecionarParaExcluirColaborador(idColaborador) {
            window.location.href = '../src/actions/excluirColaborador.php?idColaborador=' + idColaborador;
        }

        function redirecionarParaAlterarStatusColaborador(idColaborador) {
            window.location.href = '../src/actions/alterarStatusColaborador.php?idColaborador=' + idColaborador;
        }

    </script>
<!-- Bootstrap JS and dependencies -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>                
                <script src="../src/js/DataTables/datatables.js"></script>

                <script>
                    $(document).ready(function() {
                        // Aplicar DataTables às tabelas com classe "table"
                        $('.table').DataTable({
                            paging: true,
                            pageLength: 10,
                            lengthChange: false,
                            info: false,
                            searching: false,                            
                        });
                    });
                    
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

</body>

</html>