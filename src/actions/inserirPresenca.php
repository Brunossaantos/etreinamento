<?php

// Conexões (etreinamento e gestor)
include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../database/conexao2.php');

// DAOs utilizados
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../DAO/DaoTreinamento.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');
include(__DIR__ . '/../Util/Util.php');

/**
 * Monta parâmetros padrão para manter estado da tela
 */
function montarRedirect($idTreinamento, $colaborador)
{
    return "idTreinamento=" . urlencode($idTreinamento)
        . "&idColaborador=" . urlencode($colaborador->getIdColaborador())
        . "&nome=" . urlencode($colaborador->getNomeColaborador())
        . "&matricula=" . urlencode($colaborador->getMatriculadoColaborador())
        . "&cargo=" . urlencode($colaborador->getCargo())
        . "&departamento=" . urlencode($colaborador->getDepartamentoTexto())
        . "&empresa=" . urlencode($colaborador->getEmpresaTexto())
        . "&hexadecimal=" . urlencode($colaborador->getCrachaColaborador());
}

// -------------------- PARÂMETROS --------------------
$idTreinamento = $_GET['idTreinamento'] ?? null;
$cracha = $_GET['hexadecimal'] ?? null;
$idColaboradorParam = $_GET['idColaborador'] ?? null;

// Validação básica
if (!$idTreinamento) {
    header("Location: ../../layout/listaDePresenca.php?erro=dados_invalidos");
    exit();
}

if (empty($cracha) && empty($idColaboradorParam)) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=dados_invalidos");
    exit();
}

// -------------------- SETUP --------------------
$util = new Util();
$dataAtual = $util->dataAtual();

$conn = (new Conexao())->conectar();
$connGestor = (new ConexaoGestor())->conectar();

$daoTreinamento = new DaoTreinamento($conn);
$daoPresenca = new DaoPresenca($conn);
$daoColaborador = new DaoColaborador($connGestor);

// -------------------- VALIDA TREINAMENTO --------------------
$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);

if (!$treinamento) {
    header("Location: ../../layout/listaDePresenca.php?erro=treinamento_invalido");
    exit();
}

// -------------------- BUSCA COLABORADOR --------------------
if (!empty($idColaboradorParam)) {
    // Fluxo vindo da busca por nome
    $idColaborador = $idColaboradorParam;
} else {
    // Fluxo vindo do crachá
    $idColaborador = $daoColaborador->retornarIdpeloHexa($cracha);

    // Crachá não encontrado
    if ($idColaborador == -1) {
        $daoTreinamento->salvarPresencaInvalida($cracha, $idTreinamento, $dataAtual);

        header(
            "Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento"
                . "&erro=cracha_nao_encontrado"
                . "&hexadecimal=" . urlencode($cracha)
        );
        exit();
    }
}

// Busca dados completos
$colaborador = $daoColaborador->selecionarColaborador($idColaborador);

if (!$colaborador) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=colaborador_invalido");
    exit();
}

// Monta params padrão
$params = montarRedirect($idTreinamento, $colaborador);

// -------------------- ORIGEM DO COLABORADOR --------------------
$origemColaborador = $_GET['origemColaborador'] ?? 'gestor';

// -------------------- VERIFICA DUPLICIDADE --------------------
if ($daoPresenca->verificarPresencaExistente(
    $idTreinamento,
    $colaborador->getIdColaborador(),
    $origemColaborador
)) {
    header("Location: ../../layout/listaDePresenca.php?$params&erro=presenca_duplicada");
    exit();
}

// -------------------- INSERE PRESENÇA --------------------
$inseriu = $daoPresenca->inserirPresenca(
    $treinamento->getIdTreinamento(),
    $colaborador->getIdColaborador(),
    $dataAtual,
    $origemColaborador
);

if (!$inseriu) {
    header("Location: ../../layout/listaDePresenca.php?$params&erro=erro_ao_registrar");
    exit();
}

// -------------------- SUCESSO --------------------
header("Location: ../../layout/listaDePresenca.php?$params&sucesso=presenca_registrada");
exit();