<?php
session_start();

// 🔐 Validação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

$conexao = new Conexao();
$conn = $conexao->conectar();

$daoInstrutor = new DaoInstrutor($conn);
$daoDepartamento = new DaoDepartamento($conn);

$listaDeInstrutores = $daoInstrutor->gerarListaInstrurores();
$listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <!-- RESPONSIVO CORRETO -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Treinamento</title>

    <link rel="icon" href="../imagens/favicon.ico">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonte -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

    <!-- Conteúdo -->
    <div class="flex flex-col min-h-screen md:ml-64">

        <!-- Header -->
        <?php $tituloPagina = "Cadastrar Treinamento"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- Main -->
        <main class="p-3 sm:p-6 flex-1">

            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto w-full">

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">

                    <!-- IMAGEM -->
                    <div class="flex justify-center md:justify-start items-start">
                        <img src="../imagens/treinamentos/default_treinamentos.jpg"
                            class="rounded-xl shadow-md w-full max-w-[220px] md:max-w-[260px] lg:max-w-full object-cover">
                    </div>

                    <!-- FORM -->
                    <div class="md:col-span-1 lg:col-span-2">

                        <form action="../src/actions/adicionarTreinamento.php"
                            method="post"
                            enctype="multipart/form-data"
                            class="space-y-5">

                            <!-- DESCRIÇÃO -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Descrição</label>
                                <input type="text" name="descricao"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none">
                            </div>

                            <!-- CONTEÚDO -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Conteúdo do treinamento</label>
                                <textarea name="conteudo" rows="5"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary outline-none"></textarea>
                            </div>

                            <!-- GRID -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm font-medium mb-1">Carga Horária</label>
                                    <input type="time" name="cargaHoraria"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Data</label>
                                    <input type="date" name="data"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm">
                                </div>

                            </div>

                            <!-- ARQUIVO -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Material do treinamento</label>
                                <input type="file" name="material"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm">
                            </div>

                            <!-- SELECTS -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm font-medium mb-1">Instrutor</label>
                                    <select name="instrutor"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm">

                                        <?php foreach ($listaDeInstrutores as $instrutor) {
                                            if ($instrutor->getStatusInstrutor() == 1) { ?>
                                                <option value="<?= $instrutor->getIdInstrutor() ?>">
                                                    <?= $instrutor->getNomeInstrutor() ?>
                                                </option>
                                        <?php }
                                        } ?>

                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Departamento</label>
                                    <select name="departamento"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm">

                                        <?php foreach ($listaDeDepartamentos as $departamento) {
                                            if ($departamento->getStatusDepartamento() == 1) { ?>
                                                <option value="<?= $departamento->getIdDepartamento() ?>">
                                                    <?= $departamento->getNomeDepartamento() ?>
                                                </option>
                                        <?php }
                                        } ?>

                                    </select>
                                </div>

                            </div>

                            <!-- LOCAL -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Local</label>
                                <input type="text" name="local"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm"
                                    placeholder="Local do treinamento">
                            </div>

                            <!-- STATUS -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Status</label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="radio" name="status" value="1" checked>
                                        Ativo
                                    </label>
                                    <label class="flex items-center gap-2 text-sm">
                                        <input type="radio" name="status" value="0">
                                        Inativo
                                    </label>
                                </div>
                            </div>

                            <!-- BOTÕES -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-4">

                                <button type="submit"
                                    class="w-full sm:w-auto bg-primary text-white px-6 py-3 rounded-xl hover:bg-blue-800 transition font-semibold">
                                    Salvar
                                </button>

                                <a href="gerenciarTreinamento.php"
                                    class="w-full sm:w-auto text-center bg-gray-500 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition font-semibold">
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