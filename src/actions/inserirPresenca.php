<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../DAO/DaoTreinamento.php');
include(__DIR__ . '/../Util/Util.php');




if (isset($_GET['nome']) && isset($_GET['matricula']) && isset($_GET['cargo']) && isset($_GET['departamento']) && isset($_GET['empresa']) && isset($_GET['hexadecimal'])) {
    $idTreinamento = $_GET['idTreinamento'];
    $nome = $_GET['nome'];
    $matricula = $_GET['matricula'];
    $cargo = $_GET['cargo'];
    $departamento = $_GET['departamento'];
    $empresa = $_GET['empresa'];
    $cracha = $_GET['hexadecimal'];
} else {
    $nome = "Não preenchido";
    $matricula = "Não preenchido";
    $cargo = "Não preenchido";
    $departamento = "Não preenchido";
    $empresa = "Não preenchido";
    $cracha = "Não preenchido";
}

$horaAgora = new Util();

$conexao = new Conexao();

$daoTreinamento = new DaoTreinamento($conexao->conectar());
$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);

$daoColaborador = new DaoColaborador($conexao->conectar());
$idColaborador = $daoColaborador->retornarIdpeloHexa($cracha);




if ($idColaborador == -1) {
    $daoTreinamento->salvarPresencaInvalida($cracha, $idTreinamento, $horaAgora->dataAtual());
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&nome=null&matricula=null&cargo=null&departamento=null&empresa=null&hexadecimal=null");    
    exit();
}

$colaborador = $daoColaborador->selecionarColaborador($idColaborador);



if ($daoTreinamento->inserirPresencaTreinamento($treinamento->getIdTreinamento(), $colaborador->getIdColaborador(), $horaAgora->dataAtual())) {
    $nome = $colaborador->getNomeColaborador();
    $matricula = $colaborador->getMatriculadoColaborador();
    $cargo = $colaborador->getCargo();
    $departamento = $colaborador->getDepartamentoColaborador();
    $empresa = $colaborador->getIdEmpresaColaborador();
    $hexadecimal = $colaborador->getCrachaColaborador();


    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&nome=$nome&matricula=$matricula&cargo=$cargo&departamento=$departamento&empresa=$empresa&hexadecimal=$hexadecimal");
    exit();
} else {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&nome=null&matricula=null&cargo=null&departamento=null&empresa=null&hexadecimal=null");    
    exit();
}



?>