<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos departamentos
include(__DIR__ . '/../DAO/DaoDepartamento.php');

// Captura o ID do departamento
// ALERTA: Sem validação/sanitização
$idDepartamento = $_GET['idDepartamento'];

// Instancia conexão e DAO
$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

// Verifica se existe e realiza exclusão
if (
    $daoDepartamento->selecionarDepartamento($idDepartamento) != null &&
    $daoDepartamento->excluirDepartamento($idDepartamento)
) {
    // exclusão realizada
}

// Redireciona após operação
header("Location: ../../layout/gerenciarDepartamentos.php");
exit();