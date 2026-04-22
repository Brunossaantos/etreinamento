<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos instrutores
include(__DIR__ . '/../DAO/DaoInstrutor.php');

// Captura o ID do instrutor
// ALERTA: Sem validação/sanitização
$idInstrutor = $_GET['idInstrutor'];

// Instancia conexão e DAO
$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());

// Verifica se o ID foi informado
if ($idInstrutor != null) {

    // Exclui o instrutor
    $daoInstrutor->excluirInstrutor($idInstrutor);
} else {

    // ALERTA: echo antes do header pode causar erro de redirecionamento
    echo "Erro ao excluir instrutor";
}

// Redireciona após operação
header("Location: ../../layout/gerenciarInstrutores.php");
exit();