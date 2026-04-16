<?php

include(__DIR__ . '/../database/conexao2.php'); // ✅ gestor
include(__DIR__ . '/../DAO/DaoColaborador.php');

$id = $_GET['id'] ?? null;
$idTreinamento = $_GET['idTreinamento'] ?? null;

if (!$id || !$idTreinamento) {
    header("Location: ../../layout/listaDePresenca.php?erro=dados_invalidos");
    exit();
}

// ✅ conexão correta
$conexaoGestor = new ConexaoGestor();
$conn = $conexaoGestor->conectar();

$daoColaborador = new DaoColaborador($conn);

$colaborador = $daoColaborador->selecionarColaborador($id);

if (!$colaborador) {
    header("Location: ../../layout/listaDePresenca.php?erro=colaborador_nao_encontrado");
    exit();
}

// 🔁 Redireciona preenchendo os dados corretamente
header(
    "Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento"
        . "&idColaborador=" . urlencode($colaborador->getIdColaborador())
        . "&nome=" . urlencode($colaborador->getNomeColaborador())
        . "&matricula=" . urlencode($colaborador->getMatriculadoColaborador())
        . "&cargo=" . urlencode($colaborador->getCargo())
        . "&departamento=" . urlencode($colaborador->getDepartamentoTexto()) // ✅
        . "&empresa=" . urlencode($colaborador->getEmpresaTexto()) // ✅
        . "&hexadecimal=" . urlencode($colaborador->getCrachaColaborador())
);

exit();
