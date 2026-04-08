<?php
session_start();

// 🔐 Validação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

// 🔎 Pesquisa
$pesquisaInstrutor = $_GET['pesquisaInstrutor'] ?? null;

// 🔧 Instâncias
$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());

// 🔄 Lista
$listaDeInstrutores = empty($pesquisaInstrutor)
    ? $daoInstrutor->gerarListaInstrurores()
    : $daoInstrutor->pesquisarInstrutores($pesquisaInstrutor);

// 🔧 Funções
function nomeDepartamento($daoDepartamento, $idDepartamento)
{
    $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);
    return $departamento ? $departamento->getNomeDepartamento() : "Não encontrado";
}

function statusInstrutor($status)
{
    return $status == 1 ? "Ativo" : "Inativo";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Instrutores</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <!-- Sidebar -->
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- HEADER -->
        <?php $tituloPagina = "Gerenciar Instrutores"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-6 flex-1 space-y-6">

            <!-- TOPO: Pesquisa + Botão -->
            <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col md:flex-row md:justify-between gap-4">

                <form method="GET" class="flex gap-2 flex-wrap w-full md:w-auto">
                    <input type="text" name="pesquisaInstrutor" value="<?= htmlspecialchars($pesquisaInstrutor ?? '') ?>"
                        placeholder="Pesquisar instrutor..." class="border border-gray-300 rounded-lg px-3 py-2 w-full md:w-72 focus:ring-2 focus:ring-primary">
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">Buscar</button>
                </form>

                <a href="cadastrarInstrutor.php" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black mt-2 md:mt-0">
                    + Novo Instrutor
                </a>

            </div>

            <!-- CARDS RESPONSIVOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <?php foreach ($listaDeInstrutores as $instrutor): ?>
                    <div class="bg-white p-4 rounded-lg shadow-md flex flex-col md:flex-row md:justify-between gap-4">

                        <!-- Informações -->
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Nome: <span class="font-semibold"><?= $instrutor->getNomeInstrutor() ?></span></p>
                            <p class="text-sm text-gray-500">Departamento: <span class="font-semibold"><?= nomeDepartamento($daoDepartamento, $instrutor->getDepartamentoInstrutor()) ?></span></p>
                            <p class="text-sm text-gray-500">Status: <span class="font-semibold <?= $instrutor->getStatusInstrutor() == 1 ? 'text-green-600' : 'text-red-600' ?>"><?= statusInstrutor($instrutor->getStatusInstrutor()) ?></span></p>
                        </div>

                        <!-- Ações -->
                        <div class="flex gap-2 mt-2 md:mt-0 w-full md:w-auto md:flex-col">

                            <button onclick="editar(<?= $instrutor->getIdInstrutor(); ?>)"
                                class="flex items-center justify-center bg-yellow-500 text-white px-3 py-2 text-xs md:text-sm rounded-lg hover:bg-yellow-600 transition w-full md:w-36">
                                Editar
                            </button>

                            <button onclick="excluir(<?= $instrutor->getIdInstrutor(); ?>)"
                                class="flex items-center justify-center bg-red-500 text-white px-3 py-2 text-xs md:text-sm rounded-lg hover:bg-red-600 transition w-full md:w-36">
                                Excluir
                            </button>

                            <button onclick="status(<?= $instrutor->getIdInstrutor(); ?>)"
                                class="<?= $instrutor->getStatusInstrutor() == 1
                                            ? 'bg-green-600 hover:bg-green-700'
                                            : 'bg-gray-400 hover:bg-gray-500' ?> 
                                flex items-center justify-center text-white px-3 py-2 text-xs md:text-sm rounded-lg transition w-full md:w-36">

                                <?= $instrutor->getStatusInstrutor() == 1 ? 'Ativo' : 'Inativo' ?>
                            </button>

                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

        </main>

    </div>

    <script>
        function editar(id) {
            window.location.href = 'alterarInstrutor.php?idInstrutor=' + id;
        }

        function excluir(id) {
            window.location.href = '../src/actions/excluirInstrutor.php?idInstrutor=' + id;
        }

        function status(id) {
            window.location.href = '../src/actions/alterarStatusInstrutor.php?idInstrutor=' + id;
        }
    </script>

</body>

</html>