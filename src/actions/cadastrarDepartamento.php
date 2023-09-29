<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoDepartamento.php');

$nome = $_GET['departamento'];
$status = $_GET['status'];

$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

if($daoDepartamento->adicionarDepartamento($nome)){
    header("Location: ../../layout/gerenciarDepartamentos.php");
    exit();
} else {
    header("Location: ../../layout/gerenciarDepartamentos.php");
    exit();
}

?>