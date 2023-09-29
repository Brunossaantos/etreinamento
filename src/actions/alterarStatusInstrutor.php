<?php 

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoInstrutor.php');

$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());

$idInstrutor = $_GET['idInstrutor'];

$instrutor = $daoInstrutor->selecionarInstrutor($idInstrutor);

echo $instrutor;

if($instrutor->getStatusInstrutor() == 1){
    $daoInstrutor->alterarStatusInstrutor($idInstrutor, 0);
    header("Location: ../../layout/gerenciarInstrutores.php");
    exit();
} else {
    $daoInstrutor->alterarStatusInstrutor($idInstrutor, 1);
    header("Location: ../../layout/gerenciarInstrutores.php");
    exit();
}

?>