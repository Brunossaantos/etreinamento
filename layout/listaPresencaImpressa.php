<?php
include(__DIR__ .'/../src/model/presenca.php');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de presença</title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
    <style>
        body {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            /* Centraliza a tabela horizontalmente */
        }

        td,
        tr {
            border: 1px solid #000000;
            text-align: center;
            margin: 2px;
            padding: 2px;
        }

        ul,
        li {
            text-align: left;
            list-style-type: none;
            padding: 3px;
        }

        li {
            border: 1px solid #000000;
            margin: 3px;
        }

        img {
            height: 70px;
        }

        .cabecalho {
            font-weight: bold;
        }

        .comcamp {
            text-align: left;
        }

        .nomeform {
            font-size: 15px;
            color: #115391;
            font-weight: bold;
            padding: 8px;
            border: 0px;
        }

        .noborder {
            border: 0px;
        }

        .footer {
            font-size: 13px;
            text-align: center;
            padding: 10px;
        }

        .whitepoint {
            color: #ffffff;
        }
    </style>

    <style media="print">
        body {
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            padding: 20px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr {
            page-break-inside: avoid;
            /* Evitar quebras dentro das linhas da tabela */
        }

        h1 {
            text-align: center;
        }

        @page {
            size: A4;
            margin: 0;
            align-items: center;
        }

        .page-break {
            page-break-before: always;
            /* Iniciar uma nova página antes da tabela */
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

$idTreinamento = null;
if (isset($_GET['idTreinamento'])) {
    $idTreinamento = $_GET['idTreinamento'];
}

$listaPresenca = $daoTreinamento->gerarListaPresenca($idTreinamento);

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

function horarioTreinamento($daoTreinamento, $idTreinamento)
{
    $treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
    if ($treinamento != null) {
        return $treinamento->getHorarioTreinamento();
    } else {
        return 'Erro ao buscar o horário.';
    }
}

function recuperarLocalTreinamento($daoTreinamento, $idTreinamento)
{
    $treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
    if ($treinamento != null) {
        return $treinamento->getLocalTreinamento();
    } else {
        return "Erro ao buscar o local";
    }
}

function recuperarCargaHoraria($daoTreinamento, $idTreinamento)
{
    $treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
    if ($treinamento != null) {
        return $treinamento->getCargaHoraria();
    } else {
        return "Erro ao recuperar carga horária";
    }
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


    <table>
        <tr>
            <td class="nomeform"><img src="../imagens/logotipos/udLog.png" alt=""></td>
            <td class="nomeform campoLista">LISTA DE PRESENÇA</td>
            <td class="nomeform"><img src="../imagens/logotipos/guiborLog.png" alt=""></td>
            <td class="noborder">
                <ul>
                    <li>Código: FO.LT.001</li>
                    <li>Revisão: 02</li>
                    <li>Data: 31/08/2017</li>
                    <li>Responsável: Qualidade</li>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="whitepoint">.</td>
        </tr>
        <tr>
            <td colspan="4" class="cabecalho">
                <?php echo descricaoTreinamento($daoTreinamento, $idTreinamento) ?>
            </td>
        </tr>
        <tr>
            <td class="cabecalho">Instrutor</td>
            <td class="cabecalho">Data de realização</td>
            <td class="cabecalho">Local da realização</td>
            <td class="cabecalho">Carga horaria</td>
        </tr>
        <tr>
            <td>
                <?php echo recuperarInstrutor($daoTreinamento, $daoInstrutor, $idTreinamento) ?>
            </td>
            <td>
                <?php echo $util->formatarData(dataTreinamento($daoTreinamento, $idTreinamento)) ?>
            </td>
            <td>
                <?php echo recuperarLocalTreinamento($daoTreinamento, $idTreinamento) ?>
            </td>
            <td>
                <?php echo recuperarCargaHoraria($daoTreinamento, $idTreinamento) ?>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="cabecalho">Participantes</td>
        </tr>
        <tr>
            <td class="cabecalho">Nome</td>
            <td class="cabecalho">Empresa</td>
            <td class="cabecalho">Departamento</td>
            <td class="cabecalho">Horário da presença</td>
        </tr>
        <?php foreach ($listaPresenca as $presenca) { ?>
            <tr>
                <td>
                    <?php echo nomeColaborador($daoColaborador, $presenca->getIdColaborador()) ?>
                </td>
                <td>
                    <?php echo empresaColaborador($daoColaborador, $daoEmpresa, $presenca->getIdColaborador()) ?>
                </td>
                <td>
                    <?php echo departamentoColaborador($daoColaborador, $daoDepartamento, $presenca->getIdColaborador()) ?>
                </td>
                <td>
                    <?php echo separarEfomartarData($util, $presenca->getHoraPresenca()) ?>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan=4 class="whitepoint">
                .
            </td>
        </tr>
        <tr>
            <td colspan="4" class="cabecalho comcamp footer">Presença registrada automaticamente por meio de crachá de
                identificação.</td>
        </tr>
    </table>
</body>

</html>