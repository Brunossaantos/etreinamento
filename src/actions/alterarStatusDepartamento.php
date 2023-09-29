<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoDepartamento.php');

$idDepartamento = $_GET['idDepartamento'];
$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);

if($departamento->getStatusDepartamento() == 0){
    $daoDepartamento->alterarStatusDepartamento($idDepartamento, 1);
    header("Location: ../../layout/gerenciarDepartamentos.php");
} else {
    $daoDepartamento->alterarStatusDepartamento($idDepartamento, 0);
    header("Location: ../../layout/gerenciarDepartamentos.php");
}

?>