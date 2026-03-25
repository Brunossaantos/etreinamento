<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');


$conexao = new Conexao();
$daoPresenca = new DaoPresenca($conexao->conectar());

$idTreinamento = $_GET['idTreinamento'];
$idColaborador = $_GET['idColaborador'];
$horarioPresenca = $_GET['horarioPresenca'];
$hexadecimal = $_GET['hexadecimal'];

$presenca = new Presenca($idTreinamento, $idColaborador, $horarioPresenca);
echo $presenca;

if ($daoPresenca->inserirPresenca($idTreinamento, $idColaborador, $horarioPresenca)) {
    $daoPresenca->excluirPresencaVisitante($idTreinamento, $hexadecimal);
    header("Location: ../../layout/gerarListaDePresenca.php?idTreinamento=%20$idTreinamento");
    exit();    
} else {
    header("Location: ../../layout/gerarListaDePresenca.php?idTreinamento=%20$idTreinamento");
    exit();
}


?>