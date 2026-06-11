<?php
session_start();

// Validação de login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../../index.php");
    exit();
}

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos treinamentos
include(__DIR__ . '/../DAO/DaoTreinamento.php');

// Log do sistema
include(__DIR__ . '/../Util/LogSistema.php');

// Instancia conexão e DAO
$conexao = new Conexao();
$conn = $conexao->conectar();

$daoTreinamento = new DaoTreinamento($conn);
$logSistema = new LogSistema($conn);

// Dados do formulário
$descricao = $_POST['descricao'] ?? '';
$instrutor = $_POST['instrutor'] ?? '';
$data = $_POST['data'] ?? '';
$departamento = $_POST['departamento'] ?? '';
$conteudo = $_POST['conteudo'] ?? '';
$cargaHoraria = $_POST['cargaHoraria'] ?? '';
$status = $_POST['status'] ?? 1;
$local = $_POST['local'] ?? '';

// Salva o treinamento no banco e retorna o ID
$idTreinamento = $daoTreinamento->adicionarTreinamento(
    $descricao,
    $data,
    $instrutor,
    $departamento,
    $conteudo,
    $cargaHoraria,
    $local
);

// Verifica se salvou com sucesso
if ($idTreinamento != 0) {

    $logSistema->registrar(
        'treinamento',
        'Treinamento criado',
        "ID: {$idTreinamento} | Descrição: {$descricao} | Data: {$data} | Local: {$local}"
    );

    // Verifica se houve upload de arquivo
    if (isset($_FILES['material']) && $_FILES['material']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = __DIR__ . '/../../treinamentos/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = $_FILES['material']['name'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $idTreinamento . '.' . $ext;
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['material']['tmp_name'], $uploadPath)) {
            header("Location: ../../layout/gerenciarTreinamento.php");
            exit();
        }

        header("Location: ../../layout/gerenciarTreinamento.php");
        exit();
    } else {
        header("Location: ../../layout/gerenciarTreinamento.php");
        exit();
    }
} else {
    echo "Erro ao inserir treinamento.";
}