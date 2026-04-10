<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../Util/Util.php');

// 🔒 Validação
$idTreinamento = $_GET['idTreinamento'] ?? null;
$idColaborador = $_GET['idColaborador'] ?? null;
$cracha = $_GET['hexadecimal'] ?? null;

if (!$idTreinamento || !$idColaborador || !$cracha) {
    header("Location: ../../layout/listaDePresenca.php?erro=dados_invalidos");
    exit();
}

// 🔌 Conexão
$conexao = new Conexao();
$conn = $conexao->conectar();

$daoPresenca = new DaoPresenca($conn);
$daoColaborador = new DaoColaborador($conn);
$util = new Util();

$dataAtual = $util->dataAtual();

// 🔎 Verifica se o crachá já está em outro colaborador
$colaboradorExistente = $daoColaborador->buscarPorCrachaDetalhado($cracha);

if ($colaboradorExistente && $colaboradorExistente['id'] != $idColaborador) {

    $nomeExistente = urlencode($colaboradorExistente['nome']);

    header("Location: ../../layout/listaDePresenca.php
        ?idTreinamento=$idTreinamento
        &erro=cracha_duplicado
        &nomeExistente=$nomeExistente
        &idNovo=$idColaborador
        &hexadecimal=$cracha");
    exit();
}

// 🚫 Evita presença duplicada
$jaExiste = $daoPresenca->verificarPresencaExistente($idTreinamento, $idColaborador);

if ($jaExiste) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=presenca_duplicada");
    exit();
}

// 🔄 Atualiza o crachá
$atualizou = $daoColaborador->atualizarCracha($idColaborador, $cracha);

if (!$atualizou) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=erro_ao_vincular");
    exit();
}

// 💾 Insere presença
$daoPresenca->inserirPresenca($idTreinamento, $idColaborador, $dataAtual);

// 🧹 Remove presença inválida
$daoPresenca->excluirPresencaVisitante($idTreinamento, $cracha);

// 🔁 Sucesso
header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=cracha_vinculado");
exit();
