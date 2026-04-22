<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos treinamentos
include(__DIR__ . '/../DAO/DaoTreinamento.php');

// Instancia conexão e DAO
$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());

// Dados do formulário
// ALERTA: Sem validação/sanitização
$descricao = $_POST['descricao'];
$instrutor = $_POST['instrutor'];
$data = $_POST['data'];
$departamento = $_POST['departamento'];
$conteudo = $_POST['conteudo'];
$cargaHoraria = $_POST['cargaHoraria'];
$status = $_POST['status'];
$local = $_POST['local'];

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

    // Verifica se houve upload de arquivo
    if (isset($_FILES['material']) && $_FILES['material']['error'] === UPLOAD_ERR_OK) {

        // Diretório onde os arquivos serão salvos
        $uploadDir = __DIR__ . '/../../treinamentos/';

        // Nome original do arquivo
        $fileName = $_FILES['material']['name'];

        // Gera nome único usando ID do treinamento
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $idTreinamento . '.' . $ext;

        // Caminho final do arquivo
        $uploadPath = $uploadDir . $newFileName;

        // Move o arquivo para o diretório final
        // ALERTA: Não valida tipo/extensão do arquivo (risco de upload malicioso)
        if (move_uploaded_file($_FILES['material']['tmp_name'], $uploadPath)) {
            header("Location: ../../layout/gerenciarTreinamento.php");
            exit();
        }
    } else {
        // Sem arquivo, apenas redireciona
        header("Location: ../../layout/gerenciarTreinamento.php");
        exit();
    }
} else {
    // Falha ao salvar no banco
    echo "Erro ao inserir treinamento.";
}