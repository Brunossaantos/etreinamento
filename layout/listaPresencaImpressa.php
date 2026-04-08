<?php
include(__DIR__ . '/../src/model/presenca.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de presença</title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">

    <!-- Tailwind -->
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

<?php

include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/DAO/DaoColaborador.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/Util/Util.php');

$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$daoColaborador = new DaoColaborador($conexao->conectar());
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());
$util = new Util();

$idTreinamento = $_GET['idTreinamento'] ?? null;

$listaPresenca = $daoTreinamento->gerarListaPresenca($idTreinamento);

// ===== FUNÇÕES (inalteradas) =====

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
    $i = $daoInstrutor->selecionarInstrutor($t->getInstrutor());
    return $i ? $i->getNomeInstrutor() : "-";
}

function nomeColaborador($daoColaborador, $id)
{
    $c = $daoColaborador->selecionarColaborador($id);
    return $c ? $c->getNomeColaborador() : "-";
}

function empresaColaborador($daoColaborador, $daoEmpresa, $id)
{
    $c = $daoColaborador->selecionarColaborador($id);
    $e = $daoEmpresa->selecionarEmpresa($c->getIdEmpresaColaborador());
    return $e ? $e->getNomeEmpresa() : "-";
}

function departamentoColaborador($daoColaborador, $daoDepartamento, $id)
{
    $c = $daoColaborador->selecionarColaborador($id);
    $d = $daoDepartamento->selecionarDepartamento($c->getDepartamentoColaborador());
    return $d ? $d->getNomeDepartamento() : "-";
}

function separarEfomartarData($util, $dataEHora)
{
    $s = $util->separarHoraData($dataEHora);
    return $util->formatarData($s['data']) . " " . $s['hora'];
}
?>

<body class="bg-gray-100">

<div class="max-w-5xl mx-auto bg-white p-6 shadow-md mt-6">

    <!-- Header -->
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

    <!-- Título -->
    <div class="text-center font-semibold text-lg mb-4">
        <?php echo descricaoTreinamento($daoTreinamento, $idTreinamento) ?>
    </div>

    <!-- Dados -->
    <div class="grid grid-cols-4 gap-4 text-sm mb-6">
        <div><strong>Instrutor:</strong><br><?php echo recuperarInstrutor($daoTreinamento, $daoInstrutor, $idTreinamento) ?></div>
        <div><strong>Data:</strong><br><?php echo $util->formatarData(dataTreinamento($daoTreinamento, $idTreinamento)) ?></div>
        <div><strong>Local:</strong><br><?php echo recuperarLocalTreinamento($daoTreinamento, $idTreinamento) ?></div>
        <div><strong>Carga Horária:</strong><br><?php echo recuperarCargaHoraria($daoTreinamento, $idTreinamento) ?></div>
    </div>

    <!-- Tabela -->
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
            <?php foreach ($listaPresenca as $presenca) { ?>
                <tr class="hover:bg-gray-50">
                    <td class="border p-2"><?php echo nomeColaborador($daoColaborador, $presenca->getIdColaborador()) ?></td>
                    <td class="border p-2"><?php echo empresaColaborador($daoColaborador, $daoEmpresa, $presenca->getIdColaborador()) ?></td>
                    <td class="border p-2"><?php echo departamentoColaborador($daoColaborador, $daoDepartamento, $presenca->getIdColaborador()) ?></td>
                    <td class="border p-2"><?php echo separarEfomartarData($util, $presenca->getHoraPresenca()) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="text-center text-xs mt-6 border-t pt-4">
        Presença registrada automaticamente por meio de crachá de identificação.
    </div>

</div>

</body>
</html>