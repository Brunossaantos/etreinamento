<?php

// Conexão com o banco
include(__DIR__ . '/../database/conexao.php');

// DAOs utilizados no fluxo
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');

// Garante que a requisição seja POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Instancia conexão e DAOs
    $conexao = new Conexao();
    $conn = $conexao->conectar();

    $daoColaborador = new DaoColaborador($conn);
    $daoPresenca = new DaoPresenca($conn);

    // Dados do formulário
    // ALERTA: Sem validação/sanitização
    $nome = $_POST['nome'] ?? '';
    $empresa = $_POST['empresa'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $hexadecimal = $_POST['cracha'] ?? '0';
    $matricula = $_POST['matricula'] ?? '';
    $departamento = $_POST['departamento'] ?? '';

    // ID do treinamento (quando vindo da lista de presença)
    $idTreinamento = $_POST['idTreinamento'] ?? null;

    // Garante valor padrão para crachá
    if (empty($hexadecimal)) {
        $hexadecimal = '0';
    }

    // Salva colaborador
    $salvou = $daoColaborador->adicionarColaborador(
        $nome,
        $empresa,
        $cargo,
        $hexadecimal,
        $matricula,
        $departamento
    );

    // Valida se salvou corretamente
    if (!$salvou) {
        header("Location: ../../layout/gerenciarColaboradores.php?erro=erro_ao_salvar");
        exit();
    }

    // Recupera ID do colaborador inserido
    // ALERTA: Dependência de insert_id (pode variar conforme implementação)
    if ($salvou === true) {
        $idColaborador = $conn->insert_id;
    } else {
        $idColaborador = $salvou;
    }

    // Upload da foto do colaborador
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);

        // Gera nome único para evitar conflitos
        $dataHora = date('Ymd_His');
        $nomeArquivo = "colab_" . $idColaborador . "_" . $dataHora . "." . $extensao;

        // Diretório de destino
        // ALERTA: Caminho fixo pode gerar erro se não existir
        $diretorioImagens = $_SERVER['DOCUMENT_ROOT'] . '/gestor/fotos/';
        $caminhoArquivo = $diretorioImagens . $nomeArquivo;

        // Move arquivo para o destino final
        // ALERTA: Não valida tipo/extensão (risco de upload malicioso)
        move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoArquivo);
    }

    // Define tipo de retorno
    $tipoSucesso = ($hexadecimal == '0') ? 'sem_cracha' : 'colaborador_criado';

    // Registro automático de presença (quando vindo da lista)
    if (!empty($idTreinamento)) {

        // Evita duplicidade de presença
        $jaExiste = $daoPresenca->verificarPresencaExistente($idTreinamento, $idColaborador);

        if (!$jaExiste) {

            $horario = date('Y-m-d H:i:s');

            $daoPresenca->inserirPresenca(
                $idTreinamento,
                $idColaborador,
                $horario
            );
        }

        // Redireciona para lista com presença registrada
        header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=presenca_registrada");
        exit();
    }

    // Redireciona para gerenciamento padrão
    header("Location: ../../layout/gerenciarColaboradores.php?sucesso=$tipoSucesso");
    exit();
}
