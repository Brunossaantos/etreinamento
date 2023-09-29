<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoTreinamento.php');

$idTreinamento = $_GET['idTreinamento'];
$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());

if($daoTreinamento->excluirTreinamento($idTreinamento)){
    header("Location: ../../layout/gerenciarTreinamento.php");
    exit();
} else {
    header("Location: ../../layout/gerenciarTreinamento.php");
    exit();
}

?>