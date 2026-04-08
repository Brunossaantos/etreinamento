<?php
session_start();

// 🔐 Validação de login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

// 🔧 Instâncias
$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());

// 🔎 Pesquisa
$pesquisa = $_GET['pesquisa'] ?? null;

// 🔄 Lista
$listaDeDepartamentos = empty($pesquisa)
    ? $daoDepartamento->gerarListaDepartamentos()
    : $daoDepartamento->pesquisarDepartamentos($pesquisa);

// 🔄 Ordena alfabeticamente pelo nome
usort($listaDeDepartamentos, function ($a, $b) {
    return strcmp($a->getNomeDepartamento(), $b->getNomeDepartamento());
});

// -------------------- PAGINAÇÃO --------------------
$itensPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$totalItens = count($listaDeDepartamentos);
$totalPaginas = ceil($totalItens / $itensPorPagina);

// Slice da lista que será exibida
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$listaPagina = array_slice($listaDeDepartamentos, $inicio, $itensPorPagina);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Departamentos</title>

    <link rel="icon" href="../imagens/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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

    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- HEADER -->
        <?php $tituloPagina = "Gerenciar Departamentos"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-6 flex-1 space-y-6">

            <!-- TOPO: Pesquisa + Botão -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6 flex-wrap-mobile">

                <form method="GET" class="flex gap-2 items-center flex-wrap">
                    <input type="text" name="pesquisa" placeholder="Pesquisar departamento..."
                        value="<?= htmlspecialchars($pesquisa ?? '') ?>"
                        class="border border-gray-300 rounded-lg px-3 py-2 w-full md:w-64 focus:ring-2 focus:ring-primary">

                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800">Buscar</button>
                </form>

                <a href="cadastrarDepartamento.php" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black mt-2 md:mt-0">
                    + Novo Departamento
                </a>
            </div>

            <!-- CARDS RESPONSIVOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($listaPagina as $dep): ?>
                    <div class="p-4 rounded-lg shadow-md flex flex-col md:flex-row md:justify-between gap-4
                    <?= $dep->getStatusDepartamento() == 0 ? 'bg-red-50' : 'bg-white' ?>">

                        <!-- Info -->
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Departamento:
                                <span class="font-semibold <?= $dep->getStatusDepartamento() == 0 ? 'text-red-600' : '' ?>">
                                    <?= $dep->getNomeDepartamento() ?>
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">Status:
                                <span class="font-semibold"><?= $dep->getStatusDepartamento() == 1 ? 'Ativo' : 'Inativo' ?></span>
                            </p>
                        </div>

                        <!-- Ações -->
                        <div class="flex flex-wrap md:flex-col gap-2 mt-2 md:mt-0 w-full md:w-auto">
                            <button onclick="editar(<?= $dep->getIdDepartamento() ?>)"
                                class="bg-yellow-500 text-white px-4 py-2 text-sm rounded hover:bg-yellow-600 transition flex-1 md:flex-none">
                                Editar
                            </button>
                            <button onclick="excluir(<?= $dep->getIdDepartamento() ?>)"
                                class="bg-red-500 text-white px-4 py-2 text-sm rounded hover:bg-red-600 transition flex-1 md:flex-none">
                                Excluir
                            </button>
                            <button onclick="status(<?= $dep->getIdDepartamento() ?>)"
                                class="<?= $dep->getStatusDepartamento() == 1 ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400' ?> 
                               text-white px-4 py-2 text-sm rounded transition flex-1 md:flex-none">
                                Status
                            </button>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- PAGINAÇÃO -->
            <div class="flex justify-center gap-2 mt-6">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <a href="?pagina=<?= $i ?>&pesquisa=<?= urlencode($pesquisa ?? '') ?>"
                        class="px-3 py-1 rounded <?= ($i == $paginaAtual) ? 'bg-primary text-white' : 'bg-gray-200' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

        </main>
    </div>

    <script>
        function editar(id) {
            window.location.href = 'alterarDepartamento.php?idDepartamento=' + id;
        }

        function excluir(id) {
            window.location.href = '../src/actions/excluirDepartamento.php?idDepartamento=' + id;
        }

        function status(id) {
            window.location.href = '../src/actions/alterarStatusDepartamento.php?idDepartamento=' + id;
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