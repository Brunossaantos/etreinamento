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

$conexao = new Conexao();
$conn = $conexao->conectar();

$aba = $_GET['aba'] ?? 'usuario';

$abasPermitidas = ['usuario', 'treinamento', 'erro'];

if (!in_array($aba, $abasPermitidas)) {
    $aba = 'usuario';
}

$stmt = $conn->prepare("
    SELECT 
        ID_LOG,
        TIPO_LOG,
        ACAO,
        DETALHES,
        ID_USUARIO,
        NOME_USUARIO,
        DATA_LOG
    FROM LOGS
    WHERE TIPO_LOG = ?
    ORDER BY DATA_LOG DESC
    LIMIT 300
");

$stmt->bind_param("s", $aba);
$stmt->execute();

$resultLogs = $stmt->get_result();

function labelAba($aba)
{
    if ($aba === 'usuario') return 'Usuários';
    if ($aba === 'treinamento') return 'Treinamentos';
    if ($aba === 'erro') return 'Erros';

    return 'Logs';
}

function classeAba($abaAtual, $aba)
{
    return $abaAtual === $aba
        ? 'bg-primary text-white'
        : 'bg-gray-200 text-gray-700 hover:bg-gray-300';
}

function formatarDataLog($data)
{
    if (empty($data)) {
        return '-';
    }

    $dataObj = DateTime::createFromFormat('Y-m-d H:i:s', $data);

    if ($dataObj) {
        return $dataObj->format('d/m/Y H:i:s');
    }

    return $data;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs do Sistema</title>

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

        <?php $tituloPagina = "Logs do Sistema"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-4 sm:p-6 flex-1 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

                    <div>
                        <h2 class="text-xl font-bold text-primary">
                            Logs do Sistema
                        </h2>
                        <p class="text-sm text-gray-500">
                            Visualize ações importantes realizadas no sistema.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="?aba=usuario"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= classeAba($aba, 'usuario') ?>">
                            Usuários
                        </a>

                        <a href="?aba=treinamento"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= classeAba($aba, 'treinamento') ?>">
                            Treinamentos
                        </a>

                        <a href="?aba=erro"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= classeAba($aba, 'erro') ?>">
                            Erros
                        </a>
                    </div>

                </div>

                <div class="mb-4">
                    <span class="text-sm text-gray-500">
                        Aba atual:
                    </span>
                    <span class="text-sm font-semibold text-primary">
                        <?= labelAba($aba) ?>
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left">Data</th>
                                <th class="px-4 py-2 text-left">Usuário</th>
                                <th class="px-4 py-2 text-left">Ação</th>
                                <th class="px-4 py-2 text-left">Detalhes</th>
                                <th class="px-4 py-2 text-left">Tipo</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            <?php if ($resultLogs->num_rows > 0): ?>
                                <?php while ($log = $resultLogs->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <?= formatarDataLog($log['DATA_LOG']) ?>
                                        </td>

                                        <td class="px-4 py-2">
                                            <?= htmlspecialchars($log['NOME_USUARIO'] ?? 'Sistema') ?>
                                        </td>

                                        <td class="px-4 py-2">
                                            <?= htmlspecialchars($log['ACAO']) ?>
                                        </td>

                                        <td class="px-4 py-2">
                                            <?= nl2br(htmlspecialchars($log['DETALHES'] ?? '-')) ?>
                                        </td>

                                        <td class="px-4 py-2">
                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                                <?= htmlspecialchars($log['TIPO_LOG']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        Nenhum log encontrado nesta aba.
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