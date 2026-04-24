<?php
session_start();

// 🔐 Validação
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// 🔗 Conexão
require_once(__DIR__ . '/src/database/conexao.php');
$conn = (new Conexao())->conectar();

// ==========================
// 🔧 FUNÇÃO SEGURA
// ==========================
function executarQuery($conn, $sql)
{
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Erro SQL: " . mysqli_error($conn));
    }

    return $result;
}

// ==========================
// 📊 DADOS
// ==========================
$sql = "SELECT COUNT(*) as total FROM colaboradores";
$totalColaboradores = mysqli_fetch_assoc(executarQuery($conn, $sql))['total'] ?? 0;

$sql = "SELECT COUNT(*) as total FROM treinamento";
$totalTreinamentos = mysqli_fetch_assoc(executarQuery($conn, $sql))['total'] ?? 0;

$sql = "SELECT COUNT(*) as total FROM empresa";
$totalEmpresas = mysqli_fetch_assoc(executarQuery($conn, $sql))['total'] ?? 0;

// ==========================
// 📊 ÚLTIMOS
// ==========================
$sqlUltimos = "
    SELECT NOME, CARGO 
    FROM colaboradores 
    ORDER BY ID_COLABORADOR DESC 
    LIMIT 5
";

$resultUltimos = mysqli_query($conn, $sqlUltimos);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Treinamento</title>

    <link rel="icon" href="imagens/favicon.ico">

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
    <?php include(__DIR__ . '/src/Util/sidebar.php'); ?>

    <!-- Conteúdo -->
    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- HEADER -->
        <?php $tituloPagina = "Dashboard"; ?>
        <?php include(__DIR__ . '/src/Util/header.php'); ?>

        <!-- MAIN -->
        <main class="p-4 sm:p-6 flex-1">

            <!-- ✅ MENSAGEM DE SUCESSO -->
            <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'usuario_cadastrado'): ?>
                <script>
                    alert('Usuário cadastrado com sucesso ✅');

                    // remove o parâmetro da URL depois do alert
                    if (window.history.replaceState) {
                        const url = new URL(window.location);
                        url.searchParams.delete('sucesso');
                        window.history.replaceState({}, document.title, url.pathname + url.search);
                    }
                </script>
            <?php endif; ?>

            <!-- CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">

                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Colaboradores</p>
                        <h3 class="text-2xl font-bold"><?= $totalColaboradores ?></h3>
                    </div>
                    <div class="bg-blue-100 text-primary p-3 rounded-full text-xl">👤</div>
                </div>

                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Treinamentos</p>
                        <h3 class="text-2xl font-bold"><?= $totalTreinamentos ?></h3>
                    </div>
                    <div class="bg-green-100 text-green-600 p-3 rounded-full text-xl">🎓</div>
                </div>

                <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Empresas</p>
                        <h3 class="text-2xl font-bold"><?= $totalEmpresas ?></h3>
                    </div>
                    <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full text-xl">🏢</div>
                </div>

            </div>

            <!-- AÇÕES RÁPIDAS -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mb-6">
                <h3 class="text-md font-semibold mb-4">Ações rápidas</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                    <a href="layout/gerenciarColaboradores.php"
                        class="flex items-center gap-3 border rounded-xl p-4 
                               hover:border-primary hover:bg-gray-50 transition
                               active:scale-[0.98]">
                        👤 <span>Gerenciar Colaboradores</span>
                    </a>

                    <a href="layout/gerenciarTreinamento.php"
                        class="flex items-center gap-3 border rounded-xl p-4 
                               hover:border-primary hover:bg-gray-50 transition
                               active:scale-[0.98]">
                        🎓 <span>Gerenciar Treinamentos</span>
                    </a>

                    <a href="layout/cadastrarUsuario.php"
                        class="flex items-center gap-3 border rounded-xl p-4 
                               hover:border-primary hover:bg-gray-50 transition
                               active:scale-[0.98]">
                        ➕ <span>Novo Usuário</span>
                    </a>

                </div>
            </div>

            <!-- ÚLTIMOS -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                <h3 class="text-md font-semibold mb-4">
                    Últimos colaboradores cadastrados
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[400px]">
                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="pb-2">Nome</th>
                                <th class="pb-2">Cargo</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if ($resultUltimos && mysqli_num_rows($resultUltimos) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($resultUltimos)): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2"><?= $row['NOME'] ?></td>
                                        <td class="py-2"><?= $row['CARGO'] ?? '-' ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="py-3 text-center text-gray-500">
                                        Nenhum registro encontrado
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

            </div>

        </main>

    </div>

</body>

</html>