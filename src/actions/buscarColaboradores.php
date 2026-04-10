<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');

$conexao = new Conexao();
$conn = $conexao->conectar();

$dao = new DaoColaborador($conn);

$busca = $_GET['busca'] ?? "";

$colaboradores = $dao->listarColaboradores($busca);

$resultado = [];

foreach ($colaboradores as $c) {
    $resultado[] = [
        "id" => $c->getIdColaborador(),
        "nome" => $c->getNomeColaborador(),
        "matricula" => $c->getMatriculadoColaborador(),
        "cargo" => $c->getCargo()
    ];
}

header('Content-Type: application/json');
echo json_encode($resultado);