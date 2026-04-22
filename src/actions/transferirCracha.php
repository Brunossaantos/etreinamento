<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAOs utilizados
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');
include(__DIR__ . '/../Util/Util.php');

// Parâmetros recebidos
// ALERTA: Sem validação/sanitização
$idTreinamento = $_GET['idTreinamento'] ?? null;
$idNovo = $_GET['idNovo'] ?? null;
$cracha = $_GET['hexadecimal'] ?? null;

// Validação básica
if (!$idTreinamento || !$idNovo || !$cracha) {
    header("Location: ../../layout/listaDePresenca.php?erro=dados_invalidos");
    exit();
}

// Instancia conexão e DAOs
$conexao = new Conexao();
$conn = $conexao->conectar();

$daoColaborador = new DaoColaborador($conn);
$daoPresenca = new DaoPresenca($conn);
$util = new Util();

// Busca colaborador atual vinculado ao crachá
$atual = $daoColaborador->buscarPorCrachaDetalhado($cracha);

// Remove vínculo do crachá anterior (se existir)
if ($atual) {
    $daoColaborador->atualizarCracha($atual['id'], null);
}

// Vincula crachá ao novo colaborador
$daoColaborador->atualizarCracha($idNovo, $cracha);

// Registra presença do novo colaborador
$daoPresenca->inserirPresenca($idTreinamento, $idNovo, $util->dataAtual());

// Remove presença inválida (visitante)
$daoPresenca->excluirPresencaVisitante($idTreinamento, $cracha);

// Redireciona com sucesso
header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=cracha_transferido");
exit();