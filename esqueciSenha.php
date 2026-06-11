<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha</title>

    <link rel="icon" href="imagens/favicon.ico" type="image/x-icon">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#115391'
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="bg-white w-full max-w-md rounded-2xl shadow-lg p-6">

        <div class="flex justify-center mb-6">
            <img src="imagens/udLog.png" alt="UDLOG" class="w-44">
        </div>

        <h1 class="text-2xl font-bold text-center text-primary mb-2">
            Esqueci minha senha
        </h1>

        <p class="text-center text-gray-500 text-sm mb-6">
            Informe seu e-mail cadastrado para receber o link de redefinição.
        </p>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center text-sm">
                Se o e-mail estiver cadastrado, você receberá um link para redefinir sua senha.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center text-sm">
                Não foi possível processar a solicitação.
            </div>
        <?php endif; ?>

        <form action="src/actions/processarEsqueciSenha.php" method="post" class="space-y-4">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    E-mail
                </label>

                <input
                    type="email"
                    name="email"
                    required
                    placeholder="Digite seu e-mail"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <button
                type="submit"
                class="w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-blue-800 transition">
                Enviar link
            </button>

        </form>

        <div class="text-center mt-5">
            <a href="index.php" class="text-sm text-primary hover:underline">
                Voltar para o login
            </a>
        </div>

    </div>

</body>

</html>