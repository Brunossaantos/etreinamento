<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelas empresas
include(__DIR__ . '/../DAO/DaoEmpresa.php');

// Garante que a requisição seja POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Instancia conexão e DAO
    $conexao = new Conexao();
    $daoEmpresa = new DaoEmpresa($conexao->conectar());

    // Dados do formulário
    // ALERTA: Sem validação/sanitização
    $nomeEmpresa = $_POST['nome'];

    // Cria empresa e retorna ID gerado
    $idEmpresa = $daoEmpresa->adicionarEmpresaRetornarId($nomeEmpresa);

    // Verifica se foi enviado logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {

        $extensao = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);

        // Nome do arquivo baseado no ID da empresa
        $nomeArquivo = $idEmpresa . '.' . $extensao;

        // Diretório de destino
        // ALERTA: Caminho fixo pode causar erro se não existir
        $diretorioLogotipos = __DIR__ . '/../../imagens/logotipos/';
        $caminhoArquivo = $diretorioLogotipos . $nomeArquivo;

        // Move arquivo para o diretório final
        // ALERTA: Não valida tipo/extensão (risco de upload malicioso)
        move_uploaded_file($_FILES['logo']['tmp_name'], $caminhoArquivo);
    }

    // Redireciona após operação
    header("Location: ../../layout/gerenciarEmpresas.php");
    exit();
}