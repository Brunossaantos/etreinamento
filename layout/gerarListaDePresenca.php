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
    <title>Gerar lista de presença</title>
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

    <style media="print">
        body {
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            padding: 20px;
            font-size: 11pt;            
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #ddd;
        }

        h1 {
            text-align: center;
        }

        @page {
            size: A4;
            margin: 0;
            align-items: center;
        }

        #botaoImprimir{
            display : none ;
        }
    </style>


</head>

<?php
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoColaborador.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoPresenca.php');
include(__DIR__ . '/../src/Util/Util.php');

$idTreinamento = $_GET['idTreinamento'];
$conexao = new Conexao();
$daoColaborador = new DaoColaborador($conexao->conectar());
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoPresenca = new DaoPresenca($conexao->conectar());
$util = new Util();

$listaDePresenca = $daoTreinamento->gerarListaPresenca($idTreinamento);

function descricaoTreinamento($daoTreinamento, $idTreinamento)
{
    $treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);

    if ($treinamento != null) {
        return $treinamento->getDescricaoTreinamento();
    } else {
        return "Falha na recuperação do registro do treinamento.";
    }
}

function dataTreinamento($daoTreinamento, $idTreinamento)
{
    $treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
    if ($treinamento != null) {
        return $treinamento->getDataTreinamento();
    } else {
        return "Falha na recuperação do registro do treinamento";
    }
}

function adesaoTreinamento($daoPresenca, $idTreinamento){
    return $daoPresenca->contarPresenca($idTreinamento);
}

function recuperarInstrutor($daoTreinamento, $daoInstrutor, $idTreinamento)
{
    $treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
    $instrutor = $daoInstrutor->selecionarInstrutor($treinamento->getInstrutor());

    if ($instrutor != null) {
        return $instrutor->getNomeInstrutor();
    } else {
        return "Erro ao buscar o instrutor";
    }
}

function nomeColaborador($daoColaborador, $idColaborador)
{
    $colaborador = $daoColaborador->selecionarColaborador($idColaborador);
    if ($colaborador != null) {
        return $colaborador->getNomeColaborador();
    } else {
        return "Cadastro incompleto";
    }
}

function matriculaColaborador($daoColaborador, $idColaborador)
{
    $colaborador = $daoColaborador->selecionarColaborador($idColaborador);
    if ($colaborador != null) {
        return $colaborador->getMatriculadoColaborador();
    } else {
        return "Cadastro incompleto";
    }
}

function empresaColaborador($daoColaborador, $daoEmpresa, $idColaborador)
{
    $colaborador = $daoColaborador->selecionarColaborador($idColaborador);
    $empresa = $daoEmpresa->selecionarEmpresa($colaborador->getIdEmpresaColaborador());

    if ($empresa != null) {
        return $empresa->getNomeEmpresa();
    } else {
        return "Cadastro incompleto";
    }
}

function cargoColaborador($daoColaborador, $idColaborador)
{
    $colaborador = $daoColaborador->selecionarColaborador($idColaborador);
    if ($colaborador != null) {
        return $colaborador->getCargo();
    } else {
        return "Cadastro incompleto";
    }
}

function departamentoColaborador($daoColaborador, $daoDepartamento, $idColaborador)
{
    $colaborador = $daoColaborador->selecionarColaborador($idColaborador);
    $departamento = $daoDepartamento->selecionarDepartamento($colaborador->getDepartamentoColaborador());

    if ($departamento != null) {
        return $departamento->getNomeDepartamento();
    } else {
        return "Cadastro incompleto";
    }
}

function separarEfomartarData($util, $dataEHora)
{
    $separacao = $util->separarHoraData($dataEHora);
    return $util->formatarData($separacao['data']) . " " . $separacao['hora'];
}

?>

<body>
    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>
    <div class="container mt-5">
        <table class="table">
            <tr>
                <h1>
                    <?php echo descricaoTreinamento($daoTreinamento, $idTreinamento) ?>
                </h1>
            </tr>
            <tr>
                <td>
                    <h3>
                        <?php echo $util->formatarData(dataTreinamento($daoTreinamento, $idTreinamento)) ?>
                    </h3>
                </td>

                <td>
                    <h3>
                        <?php echo recuperarInstrutor($daoTreinamento, $daoInstrutor, $idTreinamento) ?>
                    </h3>
                </td>

                <td>
                    <h6>Adesão ao treinamento</h6>
                    <?php echo adesaoTreinamento($daoPresenca, $idTreinamento)." Colaboradores presentes"?>
                </td>
            </tr>
        </table>

        <!-- Tabela de Treinamentos -->
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Matricula</th>
                    <th>Empresa</th>
                    <th>Cargo</th>
                    <th>Departamento</th>
                    <th>Horário da presença</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de uma linha na tabela (pode adicionar mais linhas dinamicamente) -->
                <?php foreach ($listaDePresenca as $presenca) { ?>
                    <tr>
                        <td>
                            <?php echo nomeColaborador($daoColaborador, $presenca->getIdColaborador()) ?>
                        </td>
                        <td>
                            <?php echo matriculaColaborador($daoColaborador, $presenca->getIdColaborador()) ?>
                        </td>
                        <td>
                            <?php echo empresaColaborador($daoColaborador, $daoEmpresa, $presenca->getIdColaborador()) ?>
                        </td>
                        <td>
                            <?php echo cargoColaborador($daoColaborador, $presenca->getIdColaborador()) ?>
                        </td>
                        <td>
                            <?php echo departamentoColaborador($daoColaborador, $daoDepartamento, $presenca->getIdColaborador()) ?>
                        </td>
                        <td>
                            <?php echo separarEfomartarData($util, $presenca->getHoraPresenca()); ?>
                        </td>

                    </tr>
                <?php } ?>
                <!-- Outras linhas da tabela aqui... -->
            </tbody>
        </table>
        <div class="col-md-6">
            <!-- <button onclick="imprimirListaPresenca()" class="btn btn-primary" id="botaoImprimir">Imprimir Lista de Presença</button> -->
        </div>
        <div class="col-md-6">
            <!-- <button onclick="redirecionarParaImpressaoLista()" class="btn btn-success" id="botaoImprimir">Imprimir Lista de Presença padrãop comum</button> -->
            <a href="listaPresencaImpressa.php?idTreinamento=<?php echo $idTreinamento?>" class="btn btn-primary">Imprimir Lista de Presença padrão</a>
        </div>
    </div>

</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
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

<script>
    function imprimirListaPresenca() {
        // Ocultar o menu antes de imprimir
        var menuContainer = document.getElementById('menu-container');
        menuContainer.style.display = 'none';

        // Imprimir a página atual
        window.print();

        // Mostrar o menu novamente após a impressão
        menuContainer.style.display = 'block';
    }
</script>


</html>