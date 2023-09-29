<?php

include (__DIR__ . '/../database/conexao.php');
include (__DIR__ . '/../DAO/DaoColaborador.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = new Conexao();
    $daoColaborador = new DaoColaborador($conexao->conectar());

    $nome = $_POST['nome'];
    $empresa = $_POST['empresa'];
    $cargo = $_POST['cargo'];
    $hexadecimal = $_POST['cracha'];
    $matricula = $_POST['matricula'];
    $departamento = $_POST['departamento'];

    // Verifique se um arquivo de imagem foi enviado
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = $matricula . '.' . $extensao; // Nome composto pela matrícula e a extensão original

        // Diretório onde a imagem será salva
        $diretorioImagens = __DIR__ . '/../../imagens/colaboradores/';

        // Caminho completo do arquivo
        $caminhoArquivo = $diretorioImagens . $nomeArquivo;

        // Move o arquivo enviado para o diretório desejado
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoArquivo)) {
            // Atualize o caminho da foto no registro do colaborador no banco de dados
            if ($daoColaborador->adicionarColaborador($nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento, $nomeArquivo)) {
                header("Location: ../../layout/gerenciarColaboradores.php");
                exit();
            } else {
                header("Location: ../../layout/gerenciarColaboradores.php");
            }
        } else {
            echo "Erro ao fazer o upload do arquivo.";
        }
    } else {
        // Caso nenhum arquivo de imagem tenha sido enviado, atualize os outros campos do colaborador no banco de dados
        if ($daoColaborador->adicionarColaborador($nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento)) {
            header("Location: ../../layout/gerenciarColaboradores.php");
            exit();
        } else {
            header("Location: ../../layout/gerenciarColaboradores.php");
        }
    }
}
?>
