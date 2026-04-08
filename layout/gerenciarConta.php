<?php
session_start();

// 🔐 Validação de login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoUsuario.php');

// 🔧 Instâncias
$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());
$idUsuario = $_SESSION["user_id"];
$usuario = $daoUsuario->consultarUsuario($idUsuario);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Conta</title>

    <link rel="icon" href="../imagens/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
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

    <style>
        @media(max-width:768px) {
            .flex-wrap-mobile {
                flex-direction: column !important;
                gap: 0.5rem !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <!-- Sidebar -->
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <!-- Conteúdo -->
    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- HEADER -->
        <?php $tituloPagina = "Gerenciar Conta"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- MAIN -->
        <main class="p-6 flex-1 flex justify-center items-start">

            <div class="bg-white rounded-xl shadow-md p-6 w-full max-w-lg md:max-w-2xl">

                <h3 class="text-lg font-semibold mb-6">Dados da conta</h3>

                <form action="../src/actions/atualizarConta.php" method="post" class="space-y-4">

                    <input type="hidden" name="idUsuario" value="<?= $usuario->getIdUsuario() ?>">

                    <!-- Nome -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Nome</label>
                        <input type="text" name="nome"
                            value="<?= $usuario->getNome() ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email"
                            value="<?= $usuario->getEmail() ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    </div>

                    <!-- Login -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Login</label>
                        <input type="text" name="login"
                            value="<?= $usuario->getLogin() ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Status</label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="status" value="1"
                                    <?= $usuario->getStatusUsuario() == 1 ? 'checked' : '' ?>> Ativo
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="status" value="0"
                                    <?= $usuario->getStatusUsuario() == 0 ? 'checked' : '' ?>> Inativo
                            </label>
                        </div>
                    </div>

                    <!-- Alterar senha -->
                    <div>
                        <button type="button"
                            onclick="openPopup()"
                            class="bg-yellow-400 text-white px-4 py-2 rounded-lg hover:bg-yellow-500 transition w-full sm:w-auto">
                            Alterar senha
                        </button>
                    </div>

                    <!-- Botões -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button type="submit"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition w-full sm:w-auto">
                            Salvar
                        </button>

                        <a href="../index2.php"
                            class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 transition w-full sm:w-auto">
                            Cancelar
                        </a>
                    </div>

                </form>

            </div>

        </main>
    </div>

    <!-- MODAL -->
    <div id="modalSenha" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4">

        <div class="bg-white p-6 rounded-xl shadow w-full max-w-md">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Alterar senha</h2>
                <button onclick="closePopup()">✖</button>
            </div>

            <form method="post" action="../src/actions/alterarSenha.php" class="space-y-3">

                <input type="hidden" name="idUsuario" value="<?= $usuario->getIdUsuario() ?>">

                <input type="password" name="senha_atual" placeholder="Senha atual"
                    class="w-full border rounded-lg px-3 py-2">

                <input type="password" name="nova_senha" placeholder="Nova senha"
                    class="w-full border rounded-lg px-3 py-2">

                <input type="password" name="confirmar_senha" placeholder="Confirmar senha"
                    class="w-full border rounded-lg px-3 py-2">

                <div class="flex flex-col sm:flex-row justify-end gap-2 pt-2">
                    <button type="button"
                        onclick="closePopup()"
                        class="bg-gray-300 px-3 py-1 rounded w-full sm:w-auto">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="bg-green-600 text-white px-3 py-1 rounded w-full sm:w-auto">
                        Alterar
                    </button>
                </div>

            </form>

        </div>
    </div>

    <script>
        function openPopup() {
            document.getElementById('modalSenha').classList.remove('hidden');
            document.getElementById('modalSenha').classList.add('flex');
        }

        function closePopup() {
            document.getElementById('modalSenha').classList.add('hidden');
            document.getElementById('modalSenha').classList.remove('flex');
        }

        // MOBILE: toggle sidebar
        const menuBtn = document.getElementById('menuBtn');
        menuBtn?.addEventListener('click', () => {
            const sidebar = document.querySelector('aside');
            if (sidebar) sidebar.classList.toggle('hidden');
        });
    </script>

</body>

</html>