<?php
session_start();

// 🔐 Validação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/Util/Util.php');

// 🔎 Pesquisa
$pesquisaEmpresa = $_GET['pesquisaEmpresa'] ?? null;

// 🔧 Instâncias
$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$util = new Util();

// 🔄 Lista
$listaDeEmpresas = empty($pesquisaEmpresa)
    ? $daoEmpresa->gerarListaEmpresas()
    : $daoEmpresa->pesquisarEmpresas($pesquisaEmpresa);

// 🔄 Ordena do mais recente para o mais antigo pelo ID
usort($listaDeEmpresas, function ($a, $b) {
    return $b->getIdEmpresa() <=> $a->getIdEmpresa();
});
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Empresas</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <!-- SIDEBAR -->
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- HEADER -->
        <?php $tituloPagina = "Gerenciar Empresas"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-6 flex-1 space-y-6">

            <!-- TOPO: Pesquisa + Novo -->
            <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col md:flex-row md:justify-between gap-4">

                <form method="GET" class="flex gap-2 flex-wrap">
                    <input type="text" name="pesquisaEmpresa"
                        placeholder="Pesquisar empresa..."
                        value="<?= htmlspecialchars($pesquisaEmpresa ?? '') ?>"
                        class="border border-gray-300 rounded-lg px-3 py-2 w-full sm:w-72 focus:ring-2 focus:ring-primary">
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition w-full sm:w-auto">
                        Buscar
                    </button>
                </form>

                <a href="cadastrarEmpresa.php"
                    class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black transition w-full sm:w-auto text-center">
                    + Nova Empresa
                </a>

            </div>

            <!-- CARDS RESPONSIVOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <?php foreach ($listaDeEmpresas as $empresa): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md flex flex-col md:flex-row md:justify-between gap-4">

                        <!-- Informações -->
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Empresa: <span class="font-semibold"><?= $empresa->getNomeEmpresa() ?></span></p>
                            <p class="text-sm text-gray-500">Status: <span class="font-semibold <?= $empresa->getStatusEmpresa() == 1 ? 'text-green-600' : 'text-red-600' ?>"><?= $empresa->getStatusEmpresa() == 1 ? 'Ativa' : 'Inativa' ?></span></p>
                            <div class="mt-2">
                                <img src="<?= $util->montarCaminhoLogotipo(null, $empresa->getIdEmpresa()) ?>" class="h-12" onerror="this.src='../imagens/sem-logo.png'">
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex gap-2 mt-2 md:mt-0 w-full md:w-auto md:flex-col">

                            <button onclick="editar(<?= $empresa->getIdEmpresa() ?>)"
                                class="flex-1 md:flex-none flex items-center justify-center bg-yellow-500 text-white px-3 py-2 text-xs md:text-sm rounded-lg hover:bg-yellow-600 transition md:w-20">
                                Editar
                            </button>

                            <button onclick="excluir(<?= $empresa->getIdEmpresa() ?>)"
                                class="flex-1 md:flex-none flex items-center justify-center bg-red-500 text-white px-3 py-2 text-xs md:text-sm rounded-lg hover:bg-red-600 transition md:w-20">
                                Excluir
                            </button>

                            <button onclick="status(<?= $empresa->getIdEmpresa() ?>)"
                                class="flex-1 md:flex-none flex items-center justify-center 
        <?= $empresa->getStatusEmpresa() == 1
                        ? 'bg-green-600 hover:bg-green-700'
                        : 'bg-gray-400 hover:bg-gray-500' ?> 
        text-white px-3 py-2 text-xs md:text-sm rounded-lg transition md:w-20">

                                <?= $empresa->getStatusEmpresa() == 1 ? 'Ativa' : 'Inativa' ?>
                            </button>

                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

        </main>

    </div>

    <script>
        function excluir(id) {
            window.location.href = '../src/actions/excluirEmpresa.php?idEmpresa=' + id;
        }

        function editar(id) {
            window.location.href = 'atualizarEmpresa.php?idEmpresa=' + id;
        }

        function status(id) {
            window.location.href = '../src/actions/alterarStatusEmpresa.php?idEmpresa=' + id;
        }
    </script>

</body>

</html>