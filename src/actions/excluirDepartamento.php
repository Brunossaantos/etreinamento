<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoDepartamento.php');

$idDepartamento = $_GET['idDepartamento'];
$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

if($daoDepartamento->selecionarDepartamento($idDepartamento) != null && $daoDepartamento->excluirDepartamento($idDepartamento)){
    header("Location: ../../layout/gerenciarDepartamentos.php");
    exit();
} else {
    header("Location: ../../layout/gerenciarDepartamentos.php");
    exit();
}
?>