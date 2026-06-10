<?php

include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/database/conexao2.php');

include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/DAO/DaoPresenca.php');
include(__DIR__ . '/../src/DAO/DaoColaborador.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

include(__DIR__ . '/../src/Util/Util.php');

$conexao = new Conexao();
$connEtreinamento = $conexao->conectar();

$conexaoGestor = new ConexaoGestor();
$connGestor = $conexaoGestor->conectar();

$daoTreinamento = new DaoTreinamento($connEtreinamento);
$daoPresenca = new DaoPresenca($connEtreinamento);
$daoColaboradorGestor = new DaoColaborador($connGestor);
$daoInstrutor = new DaoInstrutor($connEtreinamento);
$daoEmpresa = new DaoEmpresa($connEtreinamento);
$daoDepartamento = new DaoDepartamento($connEtreinamento);

$util = new Util();

$idTreinamento = $_GET['idTreinamento'] ?? null;

$listaPresenca = $daoPresenca->gerarListaPresenca($idTreinamento);

$listaColaboradoresGestor = $daoColaboradorGestor->gerarListaColaboradores();

$colaboradoresGestor = [];
foreach ($listaColaboradoresGestor as $c) {
    $colaboradoresGestor[$c->getIdColaborador()] = $c;
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

function buscarColaboradorGestorDados($connGestor, $idColaborador)
{
    $idColaborador = (int) $idColaborador;

    $sql = "
        SELECT FILIAL, DEPTO
        FROM tb_colaboradores
        WHERE ID_COLABORADORES = $idColaborador
        LIMIT 1
    ";

    $result = mysqli_query($connGestor, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

function descricaoTreinamento($daoTreinamento, $idTreinamento)
{
    $t = $daoTreinamento->selecionarTreinamento($idTreinamento);
    return $t ? $t->getDescricaoTreinamento() : "Erro ao recuperar treinamento";
}

function dataTreinamento($daoTreinamento, $idTreinamento)
{
    $t = $daoTreinamento->selecionarTreinamento($idTreinamento);
    return $t ? $t->getDataTreinamento() : "-";
}

function recuperarLocalTreinamento($daoTreinamento, $idTreinamento)
{
    $t = $daoTreinamento->selecionarTreinamento($idTreinamento);
    return $t ? $t->getLocalTreinamento() : "-";
}

function recuperarCargaHoraria($daoTreinamento, $idTreinamento)
{
    $t = $daoTreinamento->selecionarTreinamento($idTreinamento);
    return $t ? $t->getCargaHoraria() : "-";
}

function recuperarInstrutor($daoTreinamento, $daoInstrutor, $idTreinamento)
{
    $t = $daoTreinamento->selecionarTreinamento($idTreinamento);

    if (!$t) {
        return "-";
    }

    $i = $daoInstrutor->selecionarInstrutor($t->getInstrutor());
    return $i ? $i->getNomeInstrutor() : "-";
}

function formatarDataHoraPresenca($dataHora)
{
    $dataHoraObj = DateTime::createFromFormat('d-m-Y H:i:s', $dataHora);

    if ($dataHoraObj) {
        return $dataHoraObj->format('d/m/Y H:i:s');
    }

    return $dataHora;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de presença</title>

    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            body {
                width: 21cm;
                height: 29.7cm;
                margin: 0 auto;
                padding: 20px;
                font-size: 12px;
            }

            .no-print {
                display: none;
            }

            table tr {
                page-break-inside: avoid;
            }

            @page {
                size: A4;
                margin: 20px 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="max-w-5xl mx-auto bg-white p-6 shadow-md mt-6">

        <div class="grid grid-cols-4 items-center border-b pb-4 mb-4">
            <div>
                <img src="../imagens/logotipos/udLog.png" class="h-12">
            </div>

            <div class="col-span-2 text-center font-bold text-xl text-blue-700">
                LISTA DE PRESENÇA
            </div>

            <div class="text-sm text-right">
                <p><strong>Código:</strong> FO.LT.001</p>
                <p><strong>Revisão:</strong> 02</p>
                <p><strong>Data:</strong> 31/08/2017</p>
            </div>
        </div>

        <div class="text-center font-semibold text-lg mb-4">
            <?= descricaoTreinamento($daoTreinamento, $idTreinamento) ?>
        </div>

        <div class="grid grid-cols-4 gap-4 text-sm mb-6">
            <div>
                <strong>Instrutor:</strong><br>
                <?= recuperarInstrutor($daoTreinamento, $daoInstrutor, $idTreinamento) ?>
            </div>

            <div>
                <strong>Data:</strong><br>
                <?= $util->formatarData(dataTreinamento($daoTreinamento, $idTreinamento)) ?>
            </div>

            <div>
                <strong>Local:</strong><br>
                <?= recuperarLocalTreinamento($daoTreinamento, $idTreinamento) ?>
            </div>

            <div>
                <strong>Carga Horária:</strong><br>
                <?= recuperarCargaHoraria($daoTreinamento, $idTreinamento) ?>
            </div>
        </div>

        <table class="w-full border border-gray-300 text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2 text-left">Nome</th>
                    <th class="border p-2 text-left">Empresa</th>
                    <th class="border p-2 text-left">Departamento</th>
                    <th class="border p-2 text-left">Horário</th>
                </tr>
            </thead>

            <tbody>
                <?php $presencasExibidas = []; ?>

                <?php foreach ($listaPresenca as $presenca): ?>

                    <?php
                    $idColab = $presenca->getIdColaborador();
                    $origemColaborador = $presenca->getOrigemColaborador() ?? 'etreinamento';

                    $chavePresenca = $origemColaborador . '-' . $idColab;

                    if (isset($presencasExibidas[$chavePresenca])) {
                        continue;
                    }

                    $presencasExibidas[$chavePresenca] = true;

                    if ($origemColaborador === 'gestor') {
                        if (!isset($colaboradoresGestor[$idColab])) {
                            continue;
                        }

                        $colab = $colaboradoresGestor[$idColab];

                        $dadosGestor = buscarColaboradorGestorDados($connGestor, $idColab);

                        $nomeColaborador = $colab->getNomeColaborador();
                        $nomeEmpresa = $dadosGestor['FILIAL'] ?? '-';
                        $nomeDepartamento = $dadosGestor['DEPTO'] ?? '-';
                    } else {
                        $colabAntigo = buscarColaboradorAntigo($connEtreinamento, $idColab);

                        if (!$colabAntigo) {
                            continue;
                        }

                        $nomeColaborador = $colabAntigo['NOME'] ?? '-';

                        $empresa = $daoEmpresa->selecionarEmpresa(
                            $colabAntigo['EMPRESA'] ?? 0
                        );

                        $nomeEmpresa = $empresa ? $empresa->getNomeEmpresa() : '-';

                        $departamento = $daoDepartamento->selecionarDepartamento(
                            $colabAntigo['DEPARTAMENTO'] ?? 0
                        );

                        $nomeDepartamento = $departamento
                            ? $departamento->getNomeDepartamento()
                            : '-';
                    }
                    ?>

                    <tr class="hover:bg-gray-50">
                        <td class="border p-2">
                            <?= $nomeColaborador ?>
                        </td>

                        <td class="border p-2">
                            <?= $nomeEmpresa ?>
                        </td>

                        <td class="border p-2">
                            <?= $nomeDepartamento ?>
                        </td>

                        <td class="border p-2">
                            <?= formatarDataHoraPresenca($presenca->getHoraPresenca()) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-center text-xs mt-6 border-t pt-4">
            Presença registrada automaticamente por meio de crachá de identificação.
        </div>
    </div>

</body>

</html>