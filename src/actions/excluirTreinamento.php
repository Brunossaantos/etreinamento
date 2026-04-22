<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos treinamentos
include(__DIR__ . '/../DAO/DaoTreinamento.php');

// Captura o ID do treinamento
// ALERTA: Sem validação/sanitização
$idTreinamento = $_GET['idTreinamento'];

// Instancia conexão e DAO
$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());

// Regra de negócio: só permite excluir se não houver vínculo (ex: presença)
if (!$daoTreinamento->verificarTreinamento($idTreinamento)) {

    // Exclui o treinamento
    $daoTreinamento->excluirTreinamento($idTreinamento);
}

// Redireciona após operação
header("Location: ../../layout/gerenciarTreinamento.php");
exit();