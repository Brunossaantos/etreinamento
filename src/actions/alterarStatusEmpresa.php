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

// Busca dados da empresa
$empresa = $daoEmpresa->selecionarEmpresa($idEmpresa);

// Regra de negócio: alternar status (0 = inativo / 1 = ativo)
if ($empresa->getStatusEmpresa() == 0) {

    $daoEmpresa->alterarStatusEmpresa($idEmpresa, 1);
} else {

    $daoEmpresa->alterarStatusEmpresa($idEmpresa, 0);
}

// Redireciona após alteração
header("Location: ../../layout/gerenciarEmpresas.php");
exit();