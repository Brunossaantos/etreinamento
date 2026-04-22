<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos departamentos
include(__DIR__ . '/../DAO/DaoDepartamento.php');

// Dados recebidos via GET
// ALERTA: Uso de GET para inserção e sem validação/sanitização
$nome = $_GET['departamento'];
$status = $_GET['status'];

// Instancia conexão e DAO
$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

// Adiciona novo departamento
$daoDepartamento->adicionarDepartamento($nome);

// Redireciona após operação
header("Location: ../../layout/gerenciarDepartamentos.php");
exit();