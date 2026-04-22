<?php

// Conexão com banco externo (gestor)
include(__DIR__ . '/../database/conexao2.php');

// DAO responsável pelos colaboradores
include(__DIR__ . '/../DAO/DaoColaborador.php');

// Parâmetros recebidos
// ALERTA: Sem validação/sanitização
$id = $_GET['id'] ?? null;
$idTreinamento = $_GET['idTreinamento'] ?? null;

// Validação básica dos parâmetros obrigatórios
if (!$id || !$idTreinamento) {
    header("Location: ../../layout/listaDePresenca.php?erro=dados_invalidos");
    exit();
}

// Instancia conexão e DAO
$conexaoGestor = new ConexaoGestor();
$conn = $conexaoGestor->conectar();
$daoColaborador = new DaoColaborador($conn);

// Busca colaborador pelo ID
$colaborador = $daoColaborador->selecionarColaborador($id);

// Valida se o colaborador existe
if (!$colaborador) {
    header("Location: ../../layout/listaDePresenca.php?erro=colaborador_nao_encontrado");
    exit();
}

// Redireciona preenchendo os dados do colaborador na URL
// Uso de urlencode para evitar quebra de parâmetros
header(
    "Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento"
        . "&idColaborador=" . urlencode($colaborador->getIdColaborador())
        . "&nome=" . urlencode($colaborador->getNomeColaborador())
        . "&matricula=" . urlencode($colaborador->getMatriculadoColaborador())
        . "&cargo=" . urlencode($colaborador->getCargo())
        . "&departamento=" . urlencode($colaborador->getDepartamentoTexto())
        . "&empresa=" . urlencode($colaborador->getEmpresaTexto())
        . "&hexadecimal=" . urlencode($colaborador->getCrachaColaborador())
);

exit();