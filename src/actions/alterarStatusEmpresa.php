<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoEmpresa.php');

$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());

$idEmpresa = $_GET['idEmpresa'];

$empresa = $daoEmpresa->selecionarEmpresa($idEmpresa);

if($empresa->getStatusEmpresa() == 0){
    $daoEmpresa->alterarStatusEmpresa($idEmpresa, 1);
    header("Location: ../../layout/gerenciarEmpresas.php");
    exit();
} else {
    $daoEmpresa->alterarStatusEmpresa($idEmpresa, 0);
    header("Location: ../../layout/gerenciarEmpresas.php");
    exit();
}

?>