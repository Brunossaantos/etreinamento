<?php

// Conexões (etreinamento e gestor)
include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../database/conexao2.php');

// DAOs utilizados
include(__DIR__ . '/../DAO/DaoPresenca.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../Util/Util.php');

// Parâmetros recebidos
// ALERTA: Sem validação/sanitização
$idTreinamento = $_GET['idTreinamento'] ?? null;
$idColaborador = $_GET['idColaborador'] ?? null;
$cracha = $_GET['hexadecimal'] ?? null;

// Validação básica
if (!$idTreinamento || !$idColaborador || !$cracha) {
    header("Location: ../../layout/listaDePresenca.php?erro=dados_invalidos");
    exit();
}

// Instancia conexões
$conn = (new Conexao())->conectar(); // etreinamento
$connGestor = (new ConexaoGestor())->conectar(); // gestor

// Instancia DAOs
$daoPresenca = new DaoPresenca($conn);
$daoColaborador = new DaoColaborador($connGestor);
$util = new Util();

// Data atual
$dataAtual = $util->dataAtual();

// Verifica se o crachá já está vinculado a outro colaborador
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

// Evita presença duplicada
if ($daoPresenca->verificarPresencaExistente($idTreinamento, $idColaborador)) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=presenca_duplicada");
    exit();
}

// Atualiza vínculo do crachá no gestor
if (!$daoColaborador->atualizarCracha($idColaborador, $cracha)) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=erro_ao_vincular");
    exit();
}

// Registra presença
if (!$daoPresenca->inserirPresenca($idTreinamento, $idColaborador, $dataAtual)) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=erro_ao_registrar");
    exit();
}

// Remove presença inválida
$daoPresenca->excluirPresencaVisitante($idTreinamento, $cracha);

// Redireciona com sucesso
header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=cracha_vinculado");
exit();