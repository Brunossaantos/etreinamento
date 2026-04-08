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
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$listaDeInstrutores = $daoInstrutor->gerarListaInstrurores();
$listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
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
    <div class="flex flex-col min-h-screen lg:ml-64">

        <!-- Header -->
        <?php $tituloPagina = "Cadastrar Treinamento"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- Main -->
        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-5xl mx-auto w-full">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

                    <!-- IMAGEM -->
                    <div class="flex justify-center lg:justify-start">
                        <img src="../imagens/treinamentos/default_treinamentos.jpg"
                            class="rounded-lg shadow-md max-h-32 sm:max-h-48">
                    </div>

                    <!-- FORM -->
                    <div class="lg:col-span-2">

                        <form action="../src/actions/adicionarTreinamento.php"
                            method="post"
                            enctype="multipart/form-data"
                            class="space-y-4">

                            <div>
                                <label class="block text-sm mb-1">Descrição</label>
                                <input type="text" name="descricao"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Conteúdo do treinamento</label>
                                <textarea name="conteudo" rows="5"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm mb-1">Carga Horária</label>
                                    <input type="time" name="cargaHoraria"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm mb-1">Data</label>
                                    <input type="date" name="data"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                </div>

                            </div>

                            <div>
                                <label class="block text-sm mb-1">Material do treinamento</label>
                                <input type="file" name="material"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm mb-1">Instrutor</label>
                                    <select name="instrutor"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
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
                                    <label class="block text-sm mb-1">Departamento</label>
                                    <select name="departamento"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
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

                            <div>
                                <label class="block text-sm mb-1">Local</label>
                                <input type="text" name="local"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                    placeholder="Local do treinamento">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Status</label>
                                <div class="flex gap-4 mt-1">
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
                                    class="w-full sm:w-auto bg-primary text-white px-5 py-2 rounded-lg hover:bg-blue-800 transition">
                                    Salvar
                                </button>

                                <a href="gerenciarTreinamento.php"
                                    class="w-full sm:w-auto text-center bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-700 transition">
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