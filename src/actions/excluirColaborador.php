<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');


if (isset($_GET['idColaborador'])) {
    $idColaborador = $_GET['idColaborador'];

    $conexao = new Conexao();
    $daoColaborador = new DaoColaborador($conexao->conectar());
    $daoColaborador->excluirColaborador($idColaborador);

    header("Location: ../../layout/gerenciarColaboradores.php");
    exit();
}
?>