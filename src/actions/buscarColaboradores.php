<?php

include(__DIR__ . '/../database/conexao2.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');

header('Content-Type: application/json; charset=utf-8');

$busca = $_GET['busca'] ?? '';

$conexaoGestor = new ConexaoGestor();
$connGestor = $conexaoGestor->conectar();

$daoColaborador = new DaoColaborador($connGestor);

$colaboradores = $daoColaborador->listarColaboradores($busca);

$resultado = [];

foreach ($colaboradores as $c) {

    // $empresa = strtoupper(trim($c->getIdEmpresaColaborador()));

    //  if (!in_array($empresa, ['MATRIZ', 'FILIAL'])) {
    //   continue;
    // }

    $resultado[] = [
        "id" => $c->getIdColaborador(),
        "nome" => $c->getNomeColaborador(),
        "matricula" => $c->getMatriculadoColaborador(),
        "cargo" => $c->getCargo()
    ];
}

echo json_encode($resultado);