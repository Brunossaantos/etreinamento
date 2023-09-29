<?php
include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');

if (isset($_GET['idColaborador'])) {
    $conexao = new Conexao();
    $daoColaborador = new DaoColaborador($conexao->conectar());
    $idColaborador = $_GET['idColaborador'];

    echo $idColaborador."<br>";

    $statausColaborador = $daoColaborador->recuperarStatusAtualColaborador($idColaborador);

    echo $statausColaborador."<br>";

    switch($statausColaborador){
        case 0:
            $statausColaborador = 1;
            break;
        case 1:
            $statausColaborador= 0;
            break;
        default:
            $statausColaborador = 3;
    }

    echo $statausColaborador."<br>";

    $daoColaborador->alterarStatusColaborador($idColaborador, $statausColaborador);

    header("Location: ../../layout/gerenciarColaboradores.php");
    exit();
}
?>