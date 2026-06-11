<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

require_once(__DIR__ . '/../src/Util/permissoes.php');

if (!isAdmin()) {
    header("Location: ../index2.php");
    exit();
}

include(__DIR__ . '/../src/database/conexao.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: cadastrarUsuario.php");
    exit();
}

$conn = (new Conexao())->conectar();

$stmt = $conn->prepare("
    SELECT 
        ID_USUARIO,
        LOGIN,
        NOME,
        EMAIL,
        STATUS_USUARIO,
        PERFIL
    FROM usuarios
    WHERE ID_USUARIO = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    header("Location: cadastrarUsuario.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>

    <link rel="icon" href="../imagens/favicon.ico">

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

<body class="bg-gray-100 text-gray-800">

    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <div class="flex flex-col md:ml-64 min-h-screen">

        <?php $tituloPagina = "Editar Usuário"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-4 sm:p-6 flex justify-center items-start flex-1">

            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 max-w-2xl w-full">

                <form action="../src/actions/atualizarUsuario.php" method="POST" class="space-y-4">

                    <input type="hidden" name="id_usuario" value="<?= $usuario['ID_USUARIO'] ?>">

                    <div>
                        <label class="block text-sm font-medium mb-1">Nome</label>
                        <input type="text" name="nome"
                            value="<?= htmlspecialchars($usuario['NOME']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email"
                            value="<?= htmlspecialchars($usuario['EMAIL']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Login</label>
                        <input type="text" name="login"
                            value="<?= htmlspecialchars($usuario['LOGIN']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Nova senha
                        </label>
                        <input type="password" name="senha"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2"
                            placeholder="Preencha somente se quiser alterar">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Status</label>

                        <div class="flex gap-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="status" value="1"
                                    <?= ((int)$usuario['STATUS_USUARIO'] === 1) ? 'checked' : '' ?>>
                                Ativo
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio" name="status" value="0"
                                    <?= ((int)$usuario['STATUS_USUARIO'] === 0) ? 'checked' : '' ?>>
                                Inativo
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Perfil</label>

                        <div class="flex gap-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="perfil" value="1"
                                    <?= ((int)$usuario['PERFIL'] === 1) ? 'checked' : '' ?>>
                                Admin
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio" name="perfil" value="2"
                                    <?= ((int)$usuario['PERFIL'] === 2) ? 'checked' : '' ?>>
                                Usuário
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">

                        <button type="submit"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800">
                            Salvar alterações
                        </button>

                        <a href="cadastrarUsuario.php"
                            class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">
                            Voltar
                        </a>

                    </div>

                </form>

            </div>

        </main>
    </div>

</body>

</html>