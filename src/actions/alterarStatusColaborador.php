<?php

// Conexão com o banco de dados
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelas operações de colaborador
include(__DIR__ . '/../DAO/DaoColaborador.php');

// Verifica se o ID do colaborador foi enviado pela URL
if (isset($_GET['idColaborador'])) {

    // Instancia conexão e DAO
    $conexao = new Conexao();
    $daoColaborador = new DaoColaborador($conexao->conectar());

    // Captura o ID recebido
    // ALERTA: Sem validação/sanitização
    $idColaborador = $_GET['idColaborador'];

    // Recupera status atual do colaborador
    $statausColaborador = $daoColaborador->recuperarStatusAtualColaborador($idColaborador);

    // Regra de negócio: alternar status (0 = inativo / 1 = ativo)
    switch ($statausColaborador) {
        case 0:
            $statausColaborador = 1;
            break;
        case 1:
            $statausColaborador = 0;
            break;
        default:
            // ALERTA: Status inesperado
            $statausColaborador = 3;
    }

    // Atualiza o status no banco
    $daoColaborador->alterarStatusColaborador($idColaborador, $statausColaborador);

    // Redireciona para a tela de gerenciamento
    header("Location: ../../layout/gerenciarColaboradores.php");
    exit();
}