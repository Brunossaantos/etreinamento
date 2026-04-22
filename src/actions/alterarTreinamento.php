<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos treinamentos
include(__DIR__ . '/../DAO/DaoTreinamento.php');

// Dados recebidos via GET
// ALERTA: Uso de GET para atualização e sem validação/sanitização
$idTreinamento = $_GET['idTreinamento'];
$descricao = $_GET['descricao'];
$conteudo = $_GET['conteudo'];
$instrutor = $_GET['instrutor'];
$data = $_GET['data'];
$departamento = $_GET['departamento'];
$status = $_GET['status'];
$cargaHoraria = $_GET['cargaHoraria'];
$local = $_GET['local'];

// Instancia conexão e DAO
$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());

// Atualiza os dados do treinamento
$daoTreinamento->atualizarTreinamento(
    $idTreinamento,
    $descricao,
    $data,
    $instrutor,
    $departamento,
    $conteudo,
    $status,
    $cargaHoraria,
    $local
);

// Redireciona após operação
header("Location: ../../layout/gerenciarTreinamento.php");
exit();