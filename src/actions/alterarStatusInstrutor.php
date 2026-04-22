<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos instrutores
include(__DIR__ . '/../DAO/DaoInstrutor.php');

// Instancia conexão e DAO
$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());

// Captura o ID do instrutor
// ALERTA: Sem validação/sanitização
$idInstrutor = $_GET['idInstrutor'];

// Busca dados do instrutor
$instrutor = $daoInstrutor->selecionarInstrutor($idInstrutor);

// ALERTA: Echo de debug pode quebrar o header e expor dados
echo $instrutor;

// Regra de negócio: alternar status (0 = inativo / 1 = ativo)
if ($instrutor->getStatusInstrutor() == 1) {

    $daoInstrutor->alterarStatusInstrutor($idInstrutor, 0);
} else {

    $daoInstrutor->alterarStatusInstrutor($idInstrutor, 1);
}

// Redireciona após alteração
header("Location: ../../layout/gerenciarInstrutores.php");
exit();