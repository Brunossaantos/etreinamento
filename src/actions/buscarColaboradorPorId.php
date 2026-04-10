<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');

$id = $_GET['id'] ?? null;
$idTreinamento = $_GET['idTreinamento'] ?? null;

if (!$id || !$idTreinamento) {
    header("Location: ../../layout/listaDePresenca.php?erro=dados_invalidos");
    exit();
}

$conexao = new Conexao();
$conn = $conexao->conectar();

$daoColaborador = new DaoColaborador($conn);

$colaborador = $daoColaborador->selecionarColaborador($id);

if (!$colaborador) {
    header("Location: ../../layout/listaDePresenca.php?erro=colaborador_nao_encontrado");
    exit();
}

// 🔁 Redireciona preenchendo os dados
header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento"
    . "&nome=" . urlencode($colaborador->getNomeColaborador())
    . "&matricula=" . urlencode($colaborador->getMatriculadoColaborador())
    . "&cargo=" . urlencode($colaborador->getCargo())
    . "&departamento=" . urlencode($colaborador->getDepartamentoColaborador())
    . "&empresa=" . urlencode($colaborador->getIdEmpresaColaborador())
    . "&hexadecimal=" . urlencode($colaborador->getCrachaColaborador())
);

exit();