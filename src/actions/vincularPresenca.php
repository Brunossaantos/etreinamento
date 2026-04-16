<?php

include(__DIR__ . '/../database/conexao.php');   // etreinamento
include(__DIR__ . '/../database/conexao2.php');  // gestor

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

// 🔌 Conexões
$conexao = new Conexao(); // etreinamento
$conn = $conexao->conectar();

$conexaoGestor = new ConexaoGestor(); // gestor
$connGestor = $conexaoGestor->conectar();

// 📦 DAOs
$daoPresenca = new DaoPresenca($conn);              // etreinamento
$daoColaborador = new DaoColaborador($connGestor);  // gestor
$util = new Util();

$dataAtual = $util->dataAtual();

// 🔎 Verifica se o crachá já está em outro colaborador (NO GESTOR)
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

// 🚫 Evita presença duplicada (ETREINAMENTO)
$jaExiste = $daoPresenca->verificarPresencaExistente($idTreinamento, $idColaborador);

if ($jaExiste) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=presenca_duplicada");
    exit();
}

// 🔄 Atualiza o crachá (GESTOR)
$atualizou = $daoColaborador->atualizarCracha($idColaborador, $cracha);

if (!$atualizou) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=erro_ao_vincular");
    exit();
}

// 💾 Insere presença (ETREINAMENTO)
$inseriu = $daoPresenca->inserirPresenca($idTreinamento, $idColaborador, $dataAtual);

if (!$inseriu) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=erro_ao_registrar");
    exit();
}

// 🧹 Remove presença inválida
$daoPresenca->excluirPresencaVisitante($idTreinamento, $cracha);

// ✅ Sucesso
header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=cracha_vinculado");
exit();