<?php

include(__DIR__ .'/../database/conexao.php');
include(__DIR__ .'/../DAO/DaoInstrutor.php');

$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());

$nome = $_GET['nome'];
$departamento = $_GET['departamento'];
$status = $_GET['status'];

if($daoInstrutor->adicionarInstrutor($nome, $departamento)){
    header("Location: ../../layout/gerenciarInstrutores.php");
    exit();
} else {
    header("Location: ../../layout/gerenciarInstrutores.php");
    exit();
}

?>