<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../DAO/DaoTreinamento.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');
include(__DIR__ . '/../Util/Util.php');

// 🔒 Validação básica
$idTreinamento = $_GET['idTreinamento'] ?? null;
$cracha = $_GET['hexadecimal'] ?? null;

if (!$idTreinamento || !$cracha) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=dados_invalidos");
    exit();
}

// 🕒 Data atual
$util = new Util();
$dataAtual = $util->dataAtual();

// 🔌 Conexão
$conexao = new Conexao();
$conn = $conexao->conectar();

// 📦 DAOs
$daoTreinamento = new DaoTreinamento($conn);
$daoColaborador = new DaoColaborador($conn);

// 🔎 Valida treinamento
$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);

if (!$treinamento) {
    header("Location: ../../layout/listaDePresenca.php?erro=treinamento_invalido");
    exit();
}

// 🔎 Busca colaborador pelo crachá
$idColaborador = $daoColaborador->retornarIdpeloHexa($cracha);

// ❌ Crachá não encontrado
if ($idColaborador == -1) {

    // 🧾 Salva tentativa inválida
    $daoTreinamento->salvarPresencaInvalida($cracha, $idTreinamento, $dataAtual);

    // 🔁 Redireciona com opção de vincular
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=cracha_nao_encontrado&hexadecimal=" . urlencode($cracha));
    exit();
}

// 🔎 Busca dados do colaborador
$colaborador = $daoColaborador->selecionarColaborador($idColaborador);

if (!$colaborador) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=colaborador_invalido");
    exit();
}

// 🚫 Evitar duplicidade de presença (IMPORTANTE)
$daoPresenca = new DaoPresenca($conn);

$jaExiste = $daoPresenca->verificarPresencaExistente($idTreinamento, $idColaborador);

if ($jaExiste) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=presenca_duplicada");
    exit();
}

// 💾 Insere presença
$inseriu = $daoTreinamento->inserirPresencaTreinamento(
    $treinamento->getIdTreinamento(),
    $colaborador->getIdColaborador(),
    $dataAtual
);

// ❌ Erro ao inserir
if (!$inseriu) {
    header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&erro=erro_ao_registrar");
    exit();
}

// ✅ Sucesso → pega dados reais
$nome = urlencode($colaborador->getNomeColaborador());
$matricula = urlencode($colaborador->getMatriculadoColaborador());
$cargo = urlencode($colaborador->getCargo());
$departamento = urlencode($colaborador->getDepartamentoColaborador());
$empresa = urlencode($colaborador->getIdEmpresaColaborador());
$hexadecimal = urlencode($colaborador->getCrachaColaborador());

// 🔁 Redireciona com sucesso
header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=presenca_registrada&nome=$nome&matricula=$matricula&cargo=$cargo&departamento=$departamento&empresa=$empresa&hexadecimal=$hexadecimal");
exit();
