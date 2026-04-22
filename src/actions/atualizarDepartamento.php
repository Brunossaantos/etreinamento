<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos departamentos
include(__DIR__ . '/../DAO/DaoDepartamento.php');

// Instancia conexão e DAO
$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

// Dados recebidos via GET
// ALERTA: Uso de GET para atualização e sem validação/sanitização
$idDepartamento = $_GET['idDepartamento'];
$nomeDepartamento = $_GET['departamento'];
$status = $_GET['status'];

// Verifica se o departamento existe
$departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);

if ($departamento != null) {

    // Atualiza dados do departamento
    $daoDepartamento->atualizarDepartamento($idDepartamento, $nomeDepartamento, $status);
}

// Redireciona após operação
header("Location: ../../layout/gerenciarDepartamentos.php");
exit();
