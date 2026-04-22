<?php

// Conexões (etreinamento e gestor)
include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../database/conexao2.php');

// DAOs utilizados
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../DAO/DaoTreinamento.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');
include(__DIR__ . '/../Util/Util.php');

// Parâmetros recebidos
// ALERTA: Sem validação/sanitização
$idTreinamento = $_GET['idTreinamento'] ?? null;
$cracha = $_GET['hexadecimal'] ?? null;

// Validação básica
if (!$idTreinamento || !$cracha) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=dados_invalidos");
    exit();
}

// Data atual do sistema
$util = new Util();
$dataAtual = $util->dataAtual();

// Instancia conexões
$conn = (new Conexao())->conectar(); // etreinamento
$connGestor = (new ConexaoGestor())->conectar(); // gestor

// Instancia DAOs
$daoTreinamento = new DaoTreinamento($conn);
$daoPresenca = new DaoPresenca($conn);

// Busca colaborador no banco gestor
$daoColaborador = new DaoColaborador($connGestor);

// Valida treinamento
$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);

if (!$treinamento) {
    header("Location: ../../layout/listaDePresenca.php?erro=treinamento_invalido");
    exit();
}

// Busca ID do colaborador pelo crachá
$idColaborador = $daoColaborador->retornarIdpeloHexa($cracha);

// Crachá não encontrado
if ($idColaborador == -1) {

    // Registra tentativa inválida
    $daoTreinamento->salvarPresencaInvalida($cracha, $idTreinamento, $dataAtual);

    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=cracha_nao_encontrado&hexadecimal=" . urlencode($cracha));
    exit();
}

// Busca dados do colaborador
$colaborador = $daoColaborador->selecionarColaborador($idColaborador);

if (!$colaborador) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=colaborador_invalido");
    exit();
}

// Evita duplicidade de presença
if ($daoPresenca->verificarPresencaExistente($idTreinamento, $idColaborador)) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=presenca_duplicada");
    exit();
}

// Registra presença
$inseriu = $daoTreinamento->inserirPresencaTreinamento(
    $treinamento->getIdTreinamento(),
    $colaborador->getIdColaborador(),
    $dataAtual
);

// Valida inserção
if (!$inseriu) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=erro_ao_registrar");
    exit();
}

// Prepara dados para retorno
$nome = urlencode($colaborador->getNomeColaborador());
$matricula = urlencode($colaborador->getMatriculadoColaborador());
$cargo = urlencode($colaborador->getCargo());
$departamento = urlencode($colaborador->getDepartamentoTexto());
$empresa = urlencode($colaborador->getEmpresaTexto());
$hexadecimal = urlencode($colaborador->getCrachaColaborador());

// Redireciona com sucesso
header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=presenca_registrada&nome=$nome&matricula=$matricula&cargo=$cargo&departamento=$departamento&empresa=$empresa&hexadecimal=$hexadecimal");
exit();