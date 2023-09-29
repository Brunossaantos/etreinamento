<?php 

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoTreinamento.php');

$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());

$descricao = $_POST['descricao'];
$instrutor = $_POST['instrutor'];
$data = $_POST['data'];
// $hora = $_POST['horario'];
$departamento = $_POST['departamento'];
$conteudo = $_POST['conteudo'];
$cargaHoraria = $_POST['cargaHoraria'];
$status = $_POST['status'];
$local = $_POST['local'];

$idTreinamento = $daoTreinamento->adicionarTreinamento($descricao, $data, $instrutor, $departamento, $conteudo, $cargaHoraria, $local);
// Inserir os dados no banco de dados independentemente do envio de arquivo
if ($idTreinamento != 0) {
    // Verifica se um arquivo foi enviado
    if (isset($_FILES['material']) && $_FILES['material']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../treinamentos/';
        $fileName = $_FILES['material']['name'];

        // Renomeia o arquivo com base na descrição
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $idTreinamento . '.' . $ext;

        $uploadPath = $uploadDir . $newFileName;

        // Move o arquivo para o diretório de destino
        if (move_uploaded_file($_FILES['material']['tmp_name'], $uploadPath)) {
            // O arquivo foi enviado com sucesso
            // Redirecione para a página gerenciarTreinamento.php
            header("Location: ../../layout/gerenciarTreinamento.php");
            exit();
        } 
    } else {
        // Nenhum arquivo foi enviado, apenas redirecione para a página gerenciarTreinamento.php
        header("Location: ../../layout/gerenciarTreinamento.php");
        exit();
    }
} else {
    // Lida com erros na inserção de dados no banco de dados, se houver
    // Por exemplo, exiba uma mensagem de erro ou faça o que for necessário
    echo "Erro ao inserir treinamento no banco de dados.";
}
?>