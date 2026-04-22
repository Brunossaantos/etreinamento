<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos colaboradores
include(__DIR__ . '/../DAO/DaoColaborador.php');

// Verifica se o ID foi enviado
if (isset($_GET['idColaborador'])) {

    // Captura o ID
    // ALERTA: Sem validação/sanitização
    $idColaborador = $_GET['idColaborador'];

    // Instancia conexão e DAO
    $conexao = new Conexao();
    $daoColaborador = new DaoColaborador($conexao->conectar());

    // Exclui o colaborador
    $daoColaborador->excluirColaborador($idColaborador);

    // Redireciona após exclusão
    header("Location: ../../layout/gerenciarColaboradores.php");
    exit();
}