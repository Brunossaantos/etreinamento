<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoEmpresa.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = new Conexao();
    $daoEmpresa = new DaoEmpresa($conexao->conectar());
    
    $nomeEmpresa = $_POST['nome'];

    $idEmpresa = $daoEmpresa->adicionarEmpresaRetornarId($nomeEmpresa);
    
    // Verifique se um arquivo de imagem foi enviado
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = $idEmpresa . '.' . $extensao; // Nome composto pelo ID da empresa e a extensão original

        // Diretório onde a imagem será salva
        $diretorioLogotipos = __DIR__ . '/../../imagens/logotipos/';

        // Caminho completo do arquivo
        $caminhoArquivo = $diretorioLogotipos . $nomeArquivo;

        // Move o arquivo enviado para o diretório desejado
        move_uploaded_file($_FILES['logo']['tmp_name'], $caminhoArquivo);
    }

    // Redireciona para a página de gerenciamento de empresas
    header("Location: ../../layout/gerenciarEmpresas.php");
    exit();
}
?>
