<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAO responsável pelos colaboradores
include(__DIR__ . '/../DAO/DaoColaborador.php');

// Garante que a requisição seja POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Instancia conexão e DAO
    $conexao = new Conexao();
    $conn = $conexao->conectar();
    $daoColaborador = new DaoColaborador($conn);

    // Dados do formulário
    // ALERTA: Sem validação/sanitização
    $idColaborador = $_POST['idColaborador'];
    $matricula = $_POST['matricula'];
    $hexadecimal = $_POST['cracha'];
    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $departamento = $_POST['departamento'];
    $empresa = $_POST['empresa'];

    // Upload da foto do colaborador
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        // Gera nome único para o arquivo
        $dataHora = date('Ymd_His');
        $nomeArquivo = "colab_" . $idColaborador . "_" . $dataHora . "." . $extensao;

        // Diretório de destino
        // ALERTA: Caminho fixo pode causar erro se não existir ou sem permissão
        $diretorioImagens = $_SERVER['DOCUMENT_ROOT'] . '/gestor/fotos/';

        // Cria pasta se não existir
        if (!is_dir($diretorioImagens)) {
            mkdir($diretorioImagens, 0777, true);
        }

        // Remove fotos antigas do colaborador
        foreach (glob($diretorioImagens . "colab_" . $idColaborador . "_*") as $arquivo) {
            unlink($arquivo);
        }

        $caminhoArquivo = $diretorioImagens . $nomeArquivo;

        // Move arquivo para o destino final
        // ALERTA: Não valida tipo/extensão (risco de upload malicioso)
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoArquivo)) {
            echo "Erro ao fazer o upload do arquivo.";
            exit();
        }
    }

    // Atualiza dados do colaborador
    $daoColaborador->atualizarColaborador(
        $idColaborador,
        $nome,
        $empresa,
        $cargo,
        $hexadecimal,
        $matricula,
        $departamento
    );

    // Redireciona após operação
    header("Location: ../../layout/gerenciarColaboradores.php");
    exit();
}