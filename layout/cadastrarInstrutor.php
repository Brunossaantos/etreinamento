<?php
session_start();

// 🔐 Validação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Instrutor</title>

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
    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- HEADER -->
        <?php $tituloPagina = "Cadastrar Instrutor"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- MAIN -->
        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-3xl mx-auto">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- IMAGEM -->
                    <div class="flex flex-col items-center gap-4">

                        <img
                            src="../imagens/treinamentos/default_treinamentos.jpg"
                            class="h-28 sm:h-32 rounded-lg shadow object-cover">

                        <span class="text-sm text-gray-500 text-center">
                            Instrutor
                        </span>

                    </div>

                    <!-- FORM -->
                    <div class="md:col-span-2">

                        <form action="../src/actions/cadastrarInstrutor.php"
                            method="get"
                            class="space-y-4">

                            <!-- Nome -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Nome
                                </label>
                                <input type="text" name="nome"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                                    placeholder="Digite o nome do instrutor"
                                    required>
                            </div>

                            <!-- Departamento -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Departamento
                                </label>
                                <select name="departamento"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">

                                    <?php foreach ($listaDeDepartamentos as $departamento) { ?>
                                        <option value="<?= $departamento->getIdDepartamento() ?>">
                                            <?= $departamento->getNomeDepartamento() ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium mb-2">
                                    Status
                                </label>

                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="1" checked>
                                        Ativo
                                    </label>

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="0">
                                        Inativo
                                    </label>

                                </div>
                            </div>

                            <!-- BOTÕES -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-4">

                                <button type="submit"
                                    class="w-full sm:w-auto bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                                    Cadastrar
                                </button>

                                <a href="gerenciarInstrutores.php"
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