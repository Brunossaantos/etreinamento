<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/database/conexao2.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoColaborador.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoPresenca.php');
include(__DIR__ . '/../src/Util/Util.php');

$idTreinamento = $_GET['idTreinamento'] ?? null;

$conexao = new Conexao();
$conexaoGestor = new ConexaoGestor();

$connEtreinamento = $conexao->conectar();
$connGestor = $conexaoGestor->conectar();

$daoColaboradorGestor = new DaoColaborador($connGestor);

$daoTreinamento = new DaoTreinamento($connEtreinamento);
$daoEmpresa = new DaoEmpresa($connEtreinamento);
$daoDepartamento = new DaoDepartamento($connEtreinamento);
$daoInstrutor = new DaoInstrutor($connEtreinamento);
$daoPresenca = new DaoPresenca($connEtreinamento);
$util = new Util();

function buscarEmpresaGestor($connGestor, $idEmpresa)
{
    $idEmpresa = (int) $idEmpresa;

    $sql = "SELECT EMPRESA FROM tb_empresa WHERE ID_EMPRESA = $idEmpresa LIMIT 1";
    $result = mysqli_query($connGestor, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}
function buscarColaboradorAntigo($connEtreinamento, $idColaborador)
{
    $idColaborador = (int) $idColaborador;

    $sql = "
        SELECT *
        FROM colaboradores
        WHERE ID_COLABORADOR = $idColaborador
        LIMIT 1
    ";

    $result = mysqli_query($connEtreinamento, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
$instrutor = $daoInstrutor->selecionarInstrutor($treinamento->getInstrutor());

$listaDePresenca = $daoPresenca->gerarListaPresenca($idTreinamento);
$listaVisitante = $daoTreinamento->gerarListaCrachasInvalidos($idTreinamento);

$listaColaboradoresGestor = $daoColaboradorGestor->gerarListaColaboradores();

$colaboradoresGestor = [];
foreach ($listaColaboradoresGestor as $c) {
    $colaboradoresGestor[$c->getIdColaborador()] = $c;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Presença</title>
    <link rel="icon" href="../imagens/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#115391'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <div class="flex flex-col md:ml-64 min-h-screen p-6">

        <?php $tituloPagina = "Lista de Presença"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <h1 class="text-xl font-bold">
                <?= $treinamento->getDescricaoTreinamento(); ?>
            </h1>

            <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-600">
                <div>📅 <?= $util->formatarData($treinamento->getDataTreinamento()); ?></div>
                <div>👨‍🏫 <?= $instrutor ? $instrutor->getNomeInstrutor() : 'Instrutor não encontrado'; ?></div>
                <div>👥 <?= $daoPresenca->contarPresenca($idTreinamento) + $daoPresenca->contarCrachasInvalidos($idTreinamento); ?> presentes</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow mb-6 overflow-x-auto">
            <h2 class="text-lg font-bold mb-4">Lista de presença</h2>

            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nome</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Matrícula</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Empresa</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Cargo</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Departamento</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Horário</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    <?php foreach ($listaDePresenca as $presenca): ?>

                        <?php
                        $idColab = $presenca->getIdColaborador();
                        $origemColaborador = $presenca->getOrigemColaborador() ?? 'etreinamento';

                        if ($origemColaborador === 'gestor') {
                            if (!isset($colaboradoresGestor[$idColab])) {
                                continue;
                            }

                            $colab = $colaboradoresGestor[$idColab];

                            $idEmpresa = (int) $colab->getIdEmpresaColaborador();

                            if (!in_array($idEmpresa, [13, 14])) {
                                continue;
                            }

                            $empresaGestor = buscarEmpresaGestor($connGestor, $idEmpresa);
                            $nomeEmpresa = $empresaGestor['EMPRESA'] ?? '-';

                            $nomeColaborador = $colab->getNomeColaborador();
                            $matriculaColaborador = $colab->getMatriculadoColaborador();
                            $cargoColaborador = $colab->getCargo();

                            $departamento = $daoDepartamento->selecionarDepartamento($colab->getDepartamentoColaborador());
                            $nomeDepartamento = $departamento ? $departamento->getNomeDepartamento() : '-';
                        } else {

                            $colabAntigo = buscarColaboradorAntigo($connEtreinamento, $idColab);

                            if (!$colabAntigo) {
                                continue;
                            }

                            $nomeColaborador = $colabAntigo['NOME'] ?? '-';
                            $matriculaColaborador = $colabAntigo['MATRICULA'] ?? '-';
                            $cargoColaborador = $colabAntigo['CARGO'] ?? '-';

                            $empresa = $daoEmpresa->selecionarEmpresa(
                                $colabAntigo['ID_EMPRESA'] ?? 0
                            );

                            $nomeEmpresa = $empresa ? $empresa->getNomeEmpresa() : '-';

                            $departamento = $daoDepartamento->selecionarDepartamento(
                                $colabAntigo['ID_DEPARTAMENTO'] ?? 0
                            );

                            $nomeDepartamento = $departamento
                                ? $departamento->getNomeDepartamento()
                                : '-';
                        }
                        ?>

                        <tr>
                            <td class="px-4 py-2"><?= $nomeColaborador; ?></td>
                            <td class="px-4 py-2"><?= $matriculaColaborador; ?></td>
                            <td class="px-4 py-2"><?= $nomeEmpresa; ?></td>
                            <td class="px-4 py-2"><?= $cargoColaborador; ?></td>
                            <td class="px-4 py-2"><?= $nomeDepartamento; ?></td>
                            <td class="px-4 py-2">
                                <?= $util->formatarData($util->separarHoraData($presenca->getHoraPresenca())['data']); ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <?php if (!empty($listaVisitante)) { ?>
            <div class="bg-white p-6 rounded-xl shadow mb-6 overflow-x-auto">
                <h2 class="text-lg font-bold mb-4">Crachás não vinculados</h2>

                <table class="min-w-full divide-y divide-gray-200 table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Crachá</th>
                            <th class="px-4 py-2 text-left">Horário</th>
                            <th class="px-4 py-2 text-left">Ação</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        <?php $i = 1; ?>

                        <?php foreach ($listaVisitante as $v): ?>
                            <?php $selectId = "colab_$i"; ?>

                            <tr>
                                <td class="px-4 py-2"><?= $i ?></td>
                                <td class="px-4 py-2"><?= $v->getHexadecimal(); ?></td>
                                <td class="px-4 py-2">
                                    <?= $util->formatarData($util->separarHoraData($v->getHorarioDaPresenca())['data']); ?>
                                </td>
                                <td class="px-4 py-2 flex gap-2">
                                    <select id="<?= $selectId ?>" class="border border-gray-300 rounded px-2 py-1">
                                        <?php foreach ($listaColaboradoresGestor as $c): ?>
                                            <?php if (in_array((int) $c->getIdEmpresaColaborador(), [13, 14])): ?>
                                                <option value="<?= $c->getIdColaborador(); ?>">
                                                    <?= $c->getNomeColaborador(); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>

                                    <button
                                        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700"
                                        onclick="vincular(<?= $v->getIdTreinamento() ?>, '<?= $v->getHorarioDaPresenca() ?>', '<?= $v->getHexadecimal() ?>', '<?= $selectId ?>')">
                                        Vincular
                                    </button>
                                </td>
                            </tr>

                            <?php $i++; ?>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        <?php } ?>

        <div class="no-print mb-6">
            <a href="listaPresencaImpressa.php?idTreinamento=<?= $idTreinamento ?>"
                class="bg-primary text-white px-5 py-2 rounded hover:bg-blue-800">
                Imprimir Lista
            </a>
        </div>

    </div>

    <script>
        function vincular(idTreinamento, horario, hex, selectId) {
            const colab = document.getElementById(selectId).value;

            window.location.href =
                `../src/actions/vincularPresenca.php?idTreinamento=${idTreinamento}&idColaborador=${colab}&horarioPresenca=${horario}&hexadecimal=${hex}&origemColaborador=gestor`;
        }
    </script>

</body>

</html>