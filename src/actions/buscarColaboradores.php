<?php

// Conexão com banco externo (gestor)
include(__DIR__ . '/../database/conexao2.php');

// DAO responsável pelos colaboradores
include(__DIR__ . '/../DAO/DaoColaborador.php');

// Parâmetro de busca (opcional)
// ALERTA: Sem validação/sanitização
$busca = $_GET['busca'] ?? '';

// Instancia conexão e DAO
$conexaoGestor = new ConexaoGestor();
$daoColaborador = new DaoColaborador($conexaoGestor->conectar());

// Busca colaboradores conforme filtro
$colaboradores = $daoColaborador->listarColaboradores($busca);

// Monta resposta para retorno em JSON
$resultado = [];

foreach ($colaboradores as $c) {
    $resultado[] = [
        "id" => $c->getIdColaborador(),
        "nome" => $c->getNomeColaborador(),
        "matricula" => $c->getMatriculadoColaborador(),
        "cargo" => $c->getCargo()
    ];
}

// Retorna dados em formato JSON (uso comum em AJAX)
echo json_encode($resultado);