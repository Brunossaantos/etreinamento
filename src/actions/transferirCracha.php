<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');
include(__DIR__ . '/../Util/Util.php');

$idTreinamento = $_GET['idTreinamento'] ?? null;
$idNovo = $_GET['idNovo'] ?? null;
$cracha = $_GET['hexadecimal'] ?? null;

if (!$idTreinamento || !$idNovo || !$cracha) {
    header("Location: ../../layout/listaDePresenca.php?erro=dados_invalidos");
    exit();
}

$conexao = new Conexao();
$conn = $conexao->conectar();

$daoColaborador = new DaoColaborador($conn);
$daoPresenca = new DaoPresenca($conn);
$util = new Util();

// 🔎 Quem está com o crachá hoje
$atual = $daoColaborador->buscarPorCrachaDetalhado($cracha);

// 🔄 Remove do antigo
if ($atual) {
    $daoColaborador->atualizarCracha($atual['id'], null);
}

// 🔄 Vincula no novo
$daoColaborador->atualizarCracha($idNovo, $cracha);

// 💾 Registra presença
$daoPresenca->inserirPresenca($idTreinamento, $idNovo, $util->dataAtual());

// 🧹 Remove inválido
$daoPresenca->excluirPresencaVisitante($idTreinamento, $cracha);

// 🔁 Volta com sucesso
header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=cracha_transferido");
exit();