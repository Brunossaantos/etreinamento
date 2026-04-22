<?php
session_start();

// Validação de sessão (protege a tela)
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// Conexão e DAOs
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoColaborador.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/Util/Util.php');

// Parâmetro recebido
// ALERTA: Sem validação/sanitização
$idColaborador = $_GET['idColaborador'] ?? null;

// Instancia conexão e DAOs
$conexao = new Conexao();
$daoColaborador = new DaoColaborador($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());
$daoEmpresa = new DaoEmpresa($conexao->conectar());

// Busca dados do colaborador e listas auxiliares
$colaborador = $daoColaborador->selecionarColaborador($idColaborador);
$listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();
$listaDeEmpresas = $daoEmpresa->gerarListaEmpresas();

// Utilitário para manipulação de foto
$util = new Util();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Colaborador</title>

    <link rel="icon" href="../imagens/favicon.ico">

    <!-- Tailwind via CDN -->
    <!-- ALERTA: CDN em produção -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonte padrão -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Configuração visual -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#115391'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <!-- Sidebar -->
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <!-- Container principal -->
    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- Header -->
        <?php $tituloPagina = "Atualizar Colaborador"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- Conteúdo -->
        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-6xl mx-auto">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Foto do colaborador -->
                    <div class="flex flex-col items-center gap-4">

                        <img
                            src="<?= $util->montarCaminhoFoto("", $colaborador->getMatriculadoColaborador()) ?>"
                            class="h-32 sm:h-40 rounded-lg shadow object-cover">

                        <span class="text-sm text-gray-500 text-center">
                            <?= $colaborador->getNomeColaborador() ?>
                        </span>

                    </div>

                    <!-- Formulário -->
                    <div class="md:col-span-2">

                        <!-- Envia dados para atualização -->
                        <form action="../src/actions/processarAtualizacao.php"
                            method="post"
                            enctype="multipart/form-data"
                            class="space-y-4">

                            <!-- ID oculto -->
                            <input type="hidden" name="idColaborador"
                                value="<?= $colaborador->getIdColaborador() ?>">

                            <!-- Upload de foto -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Foto do Colaborador
                                </label>
                                <input type="file" name="foto"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            </div>

                            <!-- Nome -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Nome</label>
                                <input type="text" name="nome"
                                    value="<?= $colaborador->getNomeColaborador() ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Matrícula e crachá -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm font-medium mb-1">Matrícula</label>
                                    <input type="text"
                                        value="<?= $colaborador->getMatriculadoColaborador() ?>"
                                        readonly
                                        class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 cursor-not-allowed">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Crachá</label>
                                    <input type="text" name="cracha"
                                        value="<?= $colaborador->getCrachaColaborador() ?>"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                                </div>

                            </div>

                            <!-- Cargo -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Cargo</label>
                                <input type="text" name="cargo"
                                    value="<?= $colaborador->getCargo() ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Departamento -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Departamento</label>

                                <select name="departamento"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">

                                    <?php foreach ($listaDeDepartamentos as $dep) {
                                        if ($dep->getStatusDepartamento() == 1) { ?>
                                            <option value="<?= $dep->getIdDepartamento() ?>"
                                                <?= $dep->getIdDepartamento() == $colaborador->getDepartamentoColaborador() ? 'selected' : '' ?>>
                                                <?= $dep->getNomeDepartamento() ?>
                                            </option>
                                    <?php }
                                    } ?>

                                </select>
                            </div>

                            <!-- Empresa -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Empresa</label>

                                <select name="empresa"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">

                                    <?php foreach ($listaDeEmpresas as $emp) {
                                        if ($emp->getStatusEmpresa() == 1) { ?>
                                            <option value="<?= $emp->getIdEmpresa() ?>"
                                                <?= $emp->getIdEmpresa() == $colaborador->getIdEmpresaColaborador() ? 'selected' : '' ?>>
                                                <?= $emp->getNomeEmpresa() ?>
                                            </option>
                                    <?php }
                                    } ?>

                                </select>
                            </div>

                            <!-- Botões -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-4">

                                <button type="submit"
                                    class="w-full sm:w-auto bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                                    Salvar Alterações
                                </button>

                                <a href="gerenciarColaboradores.php"
                                    class="w-full sm:w-auto text-center bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                                    Cancelar
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </main>
    </div>

</body>

</html>