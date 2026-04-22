<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos instrutores
include(__DIR__ . '/../DAO/DaoInstrutor.php');

// Instancia conexão e DAO
$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());

// Dados recebidos via GET
// ALERTA: Uso de GET para inserção e sem validação/sanitização
$nome = $_GET['nome'];
$departamento = $_GET['departamento'];
$status = $_GET['status'];

// Adiciona novo instrutor
$daoInstrutor->adicionarInstrutor($nome, $departamento);

// Redireciona após operação
header("Location: ../../layout/gerenciarInstrutores.php");
exit();