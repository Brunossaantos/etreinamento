<?php
session_start();

require_once(__DIR__ . '/src/database/conexao.php');

$token = $_GET['token'] ?? '';

if (empty($token)) {
    header("Location: index.php");
    exit();
}

$conn = (new Conexao())->conectar();

$stmt = $conn->prepare("
    SELECT ID_USUARIO, NOME, token_expira
    FROM usuarios
    WHERE token_reset = ?
    LIMIT 1
");

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    header("Location: index.php?erro=token_invalido");
    exit();
}

if (strtotime($usuario['token_expira']) < time()) {
    header("Location: esqueciSenha.php?erro=token_expirado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>

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
            Redefinir senha
        </h1>

        <p class="text-center text-gray-500 text-sm mb-6">
            Olá, <?= htmlspecialchars($usuario['NOME']) ?>. Cadastre sua nova senha.
        </p>

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'senhas_diferentes'): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center text-sm">
                As senhas não conferem.
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'senha_curta'): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 text-center text-sm">
                A senha precisa ter no mínimo 6 caracteres.
            </div>
        <?php endif; ?>

        <form action="src/actions/salvarNovaSenha.php" method="post" class="space-y-4">

            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Nova senha
                </label>

                <input
                    type="password"
                    name="nova_senha"
                    required
                    minlength="6"
                    placeholder="Digite sua nova senha"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Confirmar senha
                </label>

                <input
                    type="password"
                    name="confirmar_senha"
                    required
                    minlength="6"
                    placeholder="Confirme sua nova senha"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <button
                type="submit"
                class="w-full bg-primary text-white py-3 rounded-xl font-semibold hover:bg-blue-800 transition">
                Salvar nova senha
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