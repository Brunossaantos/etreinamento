<?php

include(__DIR__ . '/../database/conexao2.php'); // ✅ gestor
include(__DIR__ . '/../DAO/DaoColaborador.php');

$busca = $_GET['busca'] ?? '';

$conexaoGestor = new ConexaoGestor();
$daoColaborador = new DaoColaborador($conexaoGestor->conectar());

$colaboradores = $daoColaborador->listarColaboradores($busca);

$resultado = [];

foreach ($colaboradores as $c) {
    $resultado[] = [
        "id" => $c->getIdColaborador(),
        "nome" => $c->getNomeColaborador(),
        "matricula" => $c->getMatriculadoColaborador(),
        "cargo" => $c->getCargo()
    ];
}

echo json_encode($resultado);
