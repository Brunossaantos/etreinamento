<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos instrutores
include(__DIR__ . '/../DAO/DaoInstrutor.php');

// Dados recebidos via GET
// ALERTA: Uso de GET para atualização e sem validação/sanitização
$idInstrutor = $_GET['idInstrutor'];
$nome = $_GET['nome'];
$departamento = $_GET['departamento'];
$status = $_GET['status'];

// Instancia conexão e DAO
$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());

// Verifica se o instrutor existe antes de atualizar
if ($daoInstrutor->selecionarInstrutor($idInstrutor) != null) {

    // Atualiza dados do instrutor
    $daoInstrutor->atualizarInstrutor($idInstrutor, $nome, $departamento, $status);
} else {

    // ALERTA: echo antes do header pode causar erro de redirecionamento
    echo "Erro ao atualizar instrutor";
}

// Redireciona após operação
header("Location: ../../layout/gerenciarInstrutores.php");
exit();