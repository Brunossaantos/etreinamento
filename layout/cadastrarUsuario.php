<?php
session_start();

// 🔐 Validação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoUsuario.php');

$idUsuario = $_SESSION["user_id"];
$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

$usuario = $daoUsuario->consultarUsuario($idUsuario);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>

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
        <?php $tituloPagina = "Cadastrar Usuário"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- MAIN -->
        <main class="p-4 sm:p-6 flex justify-center items-start sm:items-center flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-2xl w-full">

                <form action="../src/actions/cadastrarUsuario.php"
                    method="POST"
                    class="space-y-4">

                    <!-- Nome -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Nome
                        </label>
                        <input type="text" name="nome"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                            placeholder="Digite o nome completo"
                            required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Email
                        </label>
                        <input type="email" name="email"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                            placeholder="email@empresa.com"
                            required>
                    </div>

                    <!-- Login -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Login
                        </label>
                        <input type="text" name="login"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                            placeholder="Usuário para acesso"
                            required>
                    </div>

                    <!-- Senha -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Senha
                        </label>
                        <input type="password" name="senha"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                            placeholder="Digite a senha"
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

                        <a href="../index2.php"
                            class="w-full sm:w-auto text-center bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </main>
    </div>

</body>

</html>