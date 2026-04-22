<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelas empresas
include(__DIR__ . '/../DAO/DaoEmpresa.php');

// Instancia conexão e DAO
$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());

// Captura o ID da empresa
// ALERTA: Sem validação/sanitização
$idEmpresa = $_GET['idEmpresa'];

// Exclui a empresa
$daoEmpresa->excluirEmpresa($idEmpresa);

// Redireciona após operação
header("Location: ../../layout/gerenciarEmpresas.php");
exit();