<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="icon" href="imagens/favicon.ico" type="image/x-icon">

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
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-100 to-blue-100 font-sans flex items-center justify-center px-4 py-6">

    <main class="w-full max-w-sm sm:max-w-md">

        <!-- Card geral -->
        <div class="bg-white rounded-2xl shadow-xl px-6 py-8 sm:px-8 sm:py-10">

            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <img
                    src="imagens/udLog.png"
                    alt="Logo UDLOG"
                    class="w-40 sm:w-52 md:w-56 object-contain">
            </div>

            <h1 class="text-2xl font-bold text-center text-primary mb-2">
                Bem-vindo
            </h1>

            <p class="text-center text-gray-500 text-sm mb-8">
                Acesse sua conta para continuar
            </p>

            <form method="post" action="src/actions/processar_login.php" class="space-y-5">

                <!-- Usuário -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Usuário
                    </label>

                    <input
                        type="text"
                        name="login"
                        required
                        autocomplete="off"
                        placeholder="Digite seu usuário"
                        class="w-full h-12 border border-gray-300 rounded-xl px-4 text-sm
                               focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                               transition">
                </div>

                <!-- Senha -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Senha
                    </label>

                    <input
                        type="password"
                        name="senha"
                        required
                        placeholder="Digite sua senha"
                        class="w-full h-12 border border-gray-300 rounded-xl px-4 text-sm
                               focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary
                               transition">
                </div>

                <!-- Botão -->
                <button
                    type="submit"
                    class="w-full h-12 bg-primary text-white rounded-xl font-semibold
                           hover:bg-blue-800 active:scale-[0.98] transition">
                    Entrar
                </button>
                <div class="text-center mt-4">
                    <a href="esqueciSenha.php" class="text-sm text-primary hover:underline">
                        Esqueci minha senha
                    </a>
                </div>

            </form>

        </div>

    </main>

</body>

</html>