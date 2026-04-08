<?php
session_start();

// 🔐 Validação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/Util/Util.php');

$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$util = new Util();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Empresa</title>

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
        <?php $tituloPagina = "Cadastrar Empresa"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- MAIN -->
        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-4xl mx-auto">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- PREVIEW LOGO -->
                    <div class="flex flex-col items-center gap-4">

                        <img
                            src="../imagens/logotipos/default_logotipo.png"
                            class="h-28 sm:h-32 rounded-lg shadow object-contain"
                            id="previewLogo">

                        <span class="text-sm text-gray-500 text-center">
                            Pré-visualização
                        </span>

                    </div>

                    <!-- FORM -->
                    <div class="md:col-span-2">

                        <form action="../src/actions/processarCadastroEmpresa.php"
                            method="post"
                            enctype="multipart/form-data"
                            class="space-y-4">

                            <!-- Upload -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Logotipo da empresa
                                </label>
                                <input type="file" name="logo" id="logo"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            </div>

                            <!-- Nome -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Nome da empresa
                                </label>
                                <input type="text" name="nome"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                                    placeholder="Digite o nome da empresa"
                                    required>
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

                                <a href="gerenciarEmpresas.php"
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

    <!-- Preview do logo -->
    <script>
        document.getElementById('logo').addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const preview = document.getElementById('previewLogo');
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>

</body>

</html>