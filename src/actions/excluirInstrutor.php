<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoInstrutor.php');

$idInstrutor = $_GET['idInstrutor'];
$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());


if($idInstrutor != null){
    $daoInstrutor->excluirInstrutor($idInstrutor);
    header("Location: ../../layout/gerenciarInstrutores.php");
    exit();
} else {
    echo "Erro ao exluir instrutor";
    header("Location: ../../layout/gerenciarInstrutores.php");
    exit();
}

?>