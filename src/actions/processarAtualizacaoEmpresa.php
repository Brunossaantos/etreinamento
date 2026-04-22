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
    $idEmpresa = $_POST['idEmpresa'];
    $nomeEmpresa = $_POST['nome'];
    $status = $_POST['status'];

    // Verifica se foi enviado logo da empresa
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {

        $extensao = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);

        // Nome do arquivo baseado no ID da empresa
        $nomeArquivo = $idEmpresa . '.' . $extensao;

        // Diretório de destino
        // ALERTA: Caminho fixo pode causar erro se não existir
        $diretorioLogotipos = __DIR__ . '/../../imagens/logotipos/';
        $caminhoArquivo = $diretorioLogotipos . $nomeArquivo;

        // Move o arquivo para o diretório final
        // ALERTA: Não valida tipo/extensão (risco de upload malicioso)
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $caminhoArquivo)) {

            // Atualiza dados da empresa
            $daoEmpresa->atualizarEmpresa($idEmpresa, $nomeEmpresa, $status);
        } else {

            echo "Erro ao fazer o upload do arquivo.";
            exit();
        }
    } else {

        // Atualiza dados sem alteração de logo
        $daoEmpresa->atualizarEmpresa($idEmpresa, $nomeEmpresa, $status);
    }

    // Redireciona após operação
    header("Location: ../../layout/gerenciarEmpresas.php");
    exit();
}