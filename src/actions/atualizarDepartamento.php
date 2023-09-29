<?php 

include(__DIR__ .'/../database/conexao.php');
include(__DIR__ .'/../DAO/DaoDepartamento.php');

$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$idDepartamento = $_GET['idDepartamento'];
$nomeDepartamento = $_GET['departamento'];
$status = $_GET['status'];

$departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);
if($departamento != null){
    $daoDepartamento->atualizarDepartamento($idDepartamento, $nomeDepartamento, $status);
    header("Location: ../../layout/gerenciarDepartamentos.php");
    exit();
} else {
    header("Location: ../../layout/gerenciarDepartamentos.php");
    exit();
}

?>