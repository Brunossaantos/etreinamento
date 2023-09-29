<?php

include (__DIR__ . '/../database/conexao.php');
include (__DIR__ . '/../DAO/DaoColaborador.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexao = new Conexao();
    $daoColaborador = new DaoColaborador($conexao->conectar());

    $matricula = $_POST['matricula'];
    $hexadecimal = $_POST['cracha'];

    $idColaborador = $daoColaborador->retornarIdpeloHexa($hexadecimal);

    $nome = $_POST['nome'];
    $cargo = $_POST['cargo'];
    $departamento = $_POST['departamento'];
    $empresa = $_POST['empresa'];

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
            if ($daoColaborador->atualizarColaborador($idColaborador, $nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento, $nomeArquivo)) {
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
        if ($daoColaborador->atualizarColaborador($idColaborador, $nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento)) {
            header("Location: ../../layout/gerenciarColaboradores.php");
            exit();
        } else {
            header("Location: ../../layout/gerenciarColaboradores.php");
        }
    }
}
?>
