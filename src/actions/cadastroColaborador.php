<?php

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../DAO/DaoColaborador.php');
include(__DIR__ . '/../DAO/DaoPresenca.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $conexao = new Conexao();
    $conn = $conexao->conectar();

    $daoColaborador = new DaoColaborador($conn);
    $daoPresenca = new DaoPresenca($conn);

    // 📥 Dados do formulário
    $nome = $_POST['nome'] ?? '';
    $empresa = $_POST['empresa'] ?? '';
    $cargo = $_POST['cargo'] ?? '';
    $hexadecimal = $_POST['cracha'] ?? '0';
    $matricula = $_POST['matricula'] ?? '';
    $departamento = $_POST['departamento'] ?? '';

    // 🔁 Vindo da lista de presença
    $idTreinamento = $_POST['idTreinamento'] ?? null;

    // 🔒 Garantia de crachá válido
    if (empty($hexadecimal)) {
        $hexadecimal = '0';
    }

    // 📸 Upload de imagem (opcional)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = $matricula . '.' . $extensao;

        $diretorioImagens = __DIR__ . '/../../imagens/colaboradores/';
        $caminhoArquivo = $diretorioImagens . $nomeArquivo;

        move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoArquivo);
    }

    // 💾 Salva colaborador
    $salvou = $daoColaborador->adicionarColaborador(
        $nome,
        $empresa,
        $cargo,
        $hexadecimal,
        $matricula,
        $departamento
    );

    if (!$salvou) {
        header("Location: ../../layout/gerenciarColaboradores.php?erro=erro_ao_salvar");
        exit();
    }

    // 🔥 IMPORTANTE:
    // Se seu DAO retornar TRUE, pega o último ID manualmente
    if ($salvou === true) {
        $idColaborador = $conn->insert_id;
    } else {
        // Se já retornar o ID direto
        $idColaborador = $salvou;
    }

    // 🔥 DEFINE TIPO DE SUCESSO
    $tipoSucesso = ($hexadecimal == '0') ? 'sem_cracha' : 'colaborador_criado';

    // 🚀 REGISTRO AUTOMÁTICO DE PRESENÇA (SÓ SE VEIO DA LISTA)
    if (!empty($idTreinamento)) {

        // 🛑 Evita duplicidade
        $jaExiste = $daoPresenca->verificarPresencaExistente($idTreinamento, $idColaborador);

        if (!$jaExiste) {

            $horario = date('Y-m-d H:i:s');

            $daoPresenca->inserirPresenca(
                $idTreinamento,
                $idColaborador,
                $horario
            );
        }

        // 🔁 Volta já com presença registrada
        header("Location: ../../layout/listaDePresenca.php?idTreinamento=$idTreinamento&sucesso=presenca_registrada");
        exit();
    }

    // 🔁 SENÃO → GERENCIAR
    header("Location: ../../layout/gerenciarColaboradores.php?sucesso=$tipoSucesso");
    exit();
}
