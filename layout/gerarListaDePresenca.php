<?php
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

$idTreinamento = $_GET['idTreinamento'];

$conexao = new Conexao();
$conexaoGestor = new ConexaoGestor();

$daoColaborador = new DaoColaborador($conexaoGestor->conectar());
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoPresenca = new DaoPresenca($conexao->conectar());
$util = new Util();

$listaDePresenca = $daoTreinamento->gerarListaPresenca($idTreinamento);
$listaVisitante = $daoTreinamento->gerarListaCrachasInvalidos($idTreinamento);
$listaDeColaboradores = $daoColaborador->gerarListaColaboradores();

$colaboradores = [];
foreach ($listaDeColaboradores as $c) {
    $colaboradores[$c->getIdColaborador()] = $c;
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

    <!-- Sidebar -->
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <div class="flex flex-col md:ml-64 min-h-screen p-6">

        <!-- Header -->
        <?php $tituloPagina = "Lista de Presença"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- Card Treinamento -->
        <div class="bg-white p-6 rounded-xl shadow mb-6">
            <h1 class="text-xl font-bold"><?= $daoTreinamento->selecionarTreinamento($idTreinamento)->getDescricaoTreinamento(); ?></h1>
            <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-600">
                <div>📅 <?= $util->formatarData($daoTreinamento->selecionarTreinamento($idTreinamento)->getDataTreinamento()); ?></div>
                <div>👨‍🏫 <?= $daoInstrutor->selecionarInstrutor($daoTreinamento->selecionarTreinamento($idTreinamento)->getInstrutor())->getNomeInstrutor(); ?></div>
                <div>👥 <?= $daoPresenca->contarPresenca($idTreinamento) + $daoPresenca->contarCrachasInvalidos($idTreinamento); ?> presentes</div>
            </div>
        </div>

        <!-- Card Lista de Presença -->
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
                    <?php foreach ($listaDePresenca as $presenca):

                        $idColab = $presenca->getIdColaborador();

                        if (!isset($colaboradores[$idColab])) {
                            continue; // 🔥 pula registro inválido
                        }

                        $colab = $colaboradores[$idColab];

                        $empresa = $daoEmpresa->selecionarEmpresa($colab->getIdEmpresaColaborador());
                        $departamento = $daoDepartamento->selecionarDepartamento($colab->getDepartamentoColaborador());
                    ?>
                        <tr>
                            <td class="px-4 py-2"><?= $colab->getNomeColaborador(); ?></td>
                            <td class="px-4 py-2"><?= $colab->getMatriculadoColaborador(); ?></td>
                            <td class="px-4 py-2"><?= $empresa->getNomeEmpresa(); ?></td>
                            <td class="px-4 py-2"><?= $colab->getCargo(); ?></td>
                            <td class="px-4 py-2"><?= $departamento->getNomeDepartamento(); ?></td>
                            <td class="px-4 py-2"><?= $util->formatarData($util->separarHoraData($presenca->getHoraPresenca())['data']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Card Visitantes -->
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
                        <?php $i = 1;
                        foreach ($listaVisitante as $v):
                            $selectId = "colab_$i";
                        ?>
                            <tr>
                                <td class="px-4 py-2"><?= $i ?></td>
                                <td class="px-4 py-2"><?= $v->getHexadecimal(); ?></td>
                                <td class="px-4 py-2"><?= $util->formatarData($util->separarHoraData($v->getHorarioDaPresenca())['data']); ?></td>
                                <td class="px-4 py-2 flex gap-2">
                                    <select id="<?= $selectId ?>" class="border border-gray-300 rounded px-2 py-1">
                                        <?php foreach ($listaDeColaboradores as $c): ?>
                                            <option value="<?= $c->getIdColaborador(); ?>"><?= $c->getNomeColaborador(); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700"
                                        onclick="vincular(<?= $v->getIdTreinamento() ?>, '<?= $v->getHorarioDaPresenca() ?>', '<?= $v->getHexadecimal() ?>', '<?= $selectId ?>')">
                                        Vincular
                                    </button>
                                </td>
                            </tr>
                        <?php $i++;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>

        <!-- Botão Imprimir -->
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
            window.location.href = `../src/actions/vincularPresenca.php?idTreinamento=${idTreinamento}&idColaborador=${colab}&horarioPresenca=${horario}&hexadecimal=${hex}`;
        }
    </script>

</body>

</html>