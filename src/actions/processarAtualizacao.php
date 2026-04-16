<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexao = new Conexao();
    $conn = $conexao->conectar();
    $daoColaborador = new DaoColaborador($conn);

    $idColaborador = $_POST['idColaborador'];

    $matricula = $_POST['matricula'];
    $hexadecimal = $_POST['cracha'];

    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $departamento = $_POST['departamento'];
    $empresa = $_POST['empresa'];

    // 📸 Upload de imagem (novo padrão)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        // 🕒 timestamp
        $dataHora = date('Ymd_His');

        // 🆕 padrão gestor
        $nomeArquivo = "colab_" . $idColaborador . "_" . $dataHora . "." . $extensao;

        // 📁 pasta correta
        $diretorioImagens = $_SERVER['DOCUMENT_ROOT'] . '/gestor/fotos/';

        // cria pasta se não existir
        if (!is_dir($diretorioImagens)) {
            mkdir($diretorioImagens, 0777, true);
        }

        // 🔥 opcional: remove fotos antigas desse colaborador
        foreach (glob($diretorioImagens . "colab_" . $idColaborador . "_*") as $arquivo) {
            unlink($arquivo);
        }

        $caminhoArquivo = $diretorioImagens . $nomeArquivo;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoArquivo)) {
            echo "Erro ao fazer o upload do arquivo.";
            exit();
        }
    }

    // 💾 Atualiza dados (com ou sem foto)
    if ($daoColaborador->atualizarColaborador($idColaborador, $nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento)) {
        header("Location: ../../layout/gerenciarColaboradores.php");
        exit();
    } else {
        header("Location: ../../layout/gerenciarColaboradores.php");
    }
}
