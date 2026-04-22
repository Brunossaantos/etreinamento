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

// Busca dados do departamento
$departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);

// Regra de negócio: alternar status (0 = inativo / 1 = ativo)
if ($departamento->getStatusDepartamento() == 0) {

    $daoDepartamento->alterarStatusDepartamento($idDepartamento, 1);
} else {

    $daoDepartamento->alterarStatusDepartamento($idDepartamento, 0);
}

// Redireciona após alteração
header("Location: ../../layout/gerenciarDepartamentos.php");
exit();