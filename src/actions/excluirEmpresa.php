<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoEmpresa.php');

$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());

$idEmpresa = $_GET['idEmpresa'];

if($daoEmpresa->excluirEmpresa($idEmpresa)){
    header("Location: ../../layout/gerenciarEmpresas.php");
    exit();
} else {
    header("Location: ../../layout/gerenciarEmpresas.php");
    exit();
}

?>