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
include(__DIR__ . '/../src/DAO/DaoUsuario.php');

$idUsuario = $_SESSION["user_id"];
$conexao = new Conexao();
$conn = $conexao->conectar();

$daoUsuario = new DaoUsuario($conn);
$usuario = $daoUsuario->consultarUsuario($idUsuario);

$sqlUsuarios = "
    SELECT 
        ID_USUARIO,
        LOGIN,
        NOME,
        EMAIL,
        STATUS_USUARIO,
        PERFIL
    FROM usuarios
    ORDER BY NOME ASC
";

$resultUsuarios = mysqli_query($conn, $sqlUsuarios);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>

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
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <div class="flex flex-col md:ml-64 min-h-screen">

        <?php $tituloPagina = "Usuários"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-4 sm:p-6 flex-1 space-y-6">

            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'cadastrado'): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    Usuário cadastrado com sucesso.
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'atualizado'): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    Usuário atualizado com sucesso.
                </div>
            <?php endif; ?>

            <!-- FORM CADASTRO -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">

                <h2 class="text-lg font-bold text-primary mb-4">
                    Cadastrar usuário
                </h2>

                <form action="../src/actions/cadastrarUsuario.php" method="POST" class="space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium mb-1">Nome</label>
                            <input type="text" name="nome"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" name="email"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Login</label>
                            <input type="text" name="login"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Senha</label>
                            <input type="password" name="senha"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                                required>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium mb-2">Status</label>

                            <div class="flex gap-4">
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

                        <div>
                            <label class="block text-sm font-medium mb-2">Perfil</label>

                            <div class="flex gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="perfil" value="1">
                                    Admin
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio" name="perfil" value="2" checked>
                                    Usuário
                                </label>
                            </div>
                        </div>

                    </div>

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

            <!-- LISTA USUÁRIOS -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 overflow-x-auto">

                <h2 class="text-lg font-bold text-primary mb-4">
                    Usuários cadastrados
                </h2>

                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-4 py-2">Nome</th>
                            <th class="text-left px-4 py-2">Login</th>
                            <th class="text-left px-4 py-2">Email</th>
                            <th class="text-left px-4 py-2">Perfil</th>
                            <th class="text-left px-4 py-2">Status</th>
                            <th class="text-left px-4 py-2">Ações</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        <?php while ($u = mysqli_fetch_assoc($resultUsuarios)): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2"><?= htmlspecialchars($u['NOME']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($u['LOGIN']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($u['EMAIL']) ?></td>

                                <td class="px-4 py-2">
                                    <?= ((int)$u['PERFIL'] === 1) ? 'Admin' : 'Usuário' ?>
                                </td>

                                <td class="px-4 py-2">
                                    <?php if ((int)$u['STATUS_USUARIO'] === 1): ?>
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                            Ativo
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                            Inativo
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-4 py-2">
                                    <a href="editarUsuario.php?id=<?= $u['ID_USUARIO'] ?>"
                                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>
                </table>
            </div>

        </main>
    </div>

</body>

</html>