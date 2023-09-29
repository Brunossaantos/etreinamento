<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoInstrutor.php');

$idInstrutor = $_GET['idInstrutor'];
$nome = $_GET['nome'];
$departamento = $_GET['departamento'];
$status = $_GET['status'];

$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());

if($daoInstrutor->selecionarInstrutor($idInstrutor) != null){
    $daoInstrutor->atualizarInstrutor($idInstrutor, $nome, $departamento, $status);
    header("Location: ../../layout/gerenciarInstrutores.php");
    exit();
} else {
    echo "Erro ao atualizar instrutor";
    header("Location: ../../layout/gerenciarInstrutores.php");
    exit();
}

?>