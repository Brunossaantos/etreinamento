<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="icon" href="imagens/favicon.ico" type="image/x-icon">

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

<body class="bg-gray-100 font-sans min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
            <img
                src="imagens/udLog.png"
                alt="Logo"
                class="w-40 sm:w-52 md:w-64">
        </div>

        <!-- Card -->
        <div class="bg-white shadow-md rounded-lg p-6 sm:p-8">

            <h2 class="text-xl sm:text-2xl font-bold text-center text-primary mb-6">
                Login
            </h2>

            <form method="post" action="src/actions/processar_login.php">

                <!-- Usuário -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Usuário
                    </label>
                    <input
                        type="text"
                        name="login"
                        required
                        autocomplete="off"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- Senha -->
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">
                        Senha
                    </label>
                    <input
                        type="password"
                        name="senha"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 
                               focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- Botão -->
                <button
                    type="submit"
                    class="w-full bg-primary text-white py-2.5 rounded-lg 
                           hover:bg-blue-800 transition font-semibold">
                    Entrar
                </button>

            </form>
        </div>
    </div>

</body>

</html>