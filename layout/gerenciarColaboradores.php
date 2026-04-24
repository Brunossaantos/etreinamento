<?php
session_start();

// 🔐 Validação de login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/database/conexao2.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoColaborador.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/Util/util.php');

// 🔧 Instâncias
$conexao = new Conexao(); // etreinamento
$conexaoGestor = new ConexaoGestor(); // gestor

$daoColaborador = new DaoColaborador($conexaoGestor->conectar()); // ✅ AQUI MUDA

$daoDepartamento = new DaoDepartamento($conexao->conectar());
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$util = new Util();

// 🔎 Pesquisa + Filtro
$pesquisaColab = $_GET['pesquisaColab'] ?? null;
$statusFiltro = $_GET['status'] ?? 'ativos';

$listaDeColaboradores = empty($pesquisaColab)
    ? $daoColaborador->gerarListaColaboradores()
    : $daoColaborador->pesquisarColaborador($pesquisaColab);

// 🔽 Filtro de status
if ($statusFiltro !== 'todos') {
    $listaDeColaboradores = array_filter($listaDeColaboradores, function ($colab) use ($statusFiltro) {
        if ($statusFiltro == 'ativos') return $colab->getStatusColaborador() == 1;
        if ($statusFiltro == 'inativos') return $colab->getStatusColaborador() == 0;
    });
}

// 🔄 Ordena alfabeticamente pelo nome
usort($listaDeColaboradores, function ($a, $b) {
    return strcmp($a->getNomeColaborador(), $b->getNomeColaborador());
});

// -------------------- PAGINAÇÃO --------------------
$itensPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$totalItens = count($listaDeColaboradores);
$totalPaginas = ceil($totalItens / $itensPorPagina);

// Slice da lista que será exibida
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$listaPagina = array_slice($listaDeColaboradores, $inicio, $itensPorPagina);

// 🔧 Funções auxiliares
function nomeDepartamento($daoDepartamento, $idDepartamento)
{
    $dep = $daoDepartamento->selecionarDepartamento($idDepartamento);
    return $dep ? $dep->getNomeDepartamento() : "Departamento inexistente";
}

function nomeDaEmpresa($daoEmpresa, $idEmpresa)
{
    $emp = $daoEmpresa->selecionarEmpresa($idEmpresa);
    return $emp ? $emp->getNomeEmpresa() : "Empresa não cadastrada";
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Colaboradores</title>

    <link rel="icon" href="../imagens/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .thumbColab {
            height: 70px;
            border-radius: 8px;
        }

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
        <?php $tituloPagina = "Gerenciar Colaboradores"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-6 flex-1 space-y-6">

            <!-- TOPO: Pesquisa + Filtros + Botão -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6 flex-wrap-mobile">

                <form method="GET" class="flex gap-2 items-center flex-wrap">
                    <input type="text" name="pesquisaColab" placeholder="Pesquisar colaborador..."
                        value="<?= htmlspecialchars($pesquisaColab ?? '') ?>"
                        class="border border-gray-300 rounded-lg px-3 py-2 w-full md:w-64 focus:ring-2 focus:ring-primary">

                    <button class="bg-primary text-white bg-green-500 px-4 py-2 rounded-lg hover:bg-green-600">Buscar</button>

                    <div class="flex gap-2 flex-wrap mt-2 md:mt-0">
                        <a href="?status=todos" class="px-3 py-2 rounded-lg text-sm <?= ($statusFiltro == 'todos') ? 'bg-primary text-white bg-blue-500 px-4 py-2 rounded-lg hover:bg-blue-800' : 'bg-gray-200' ?>">Todos</a>
                        <a href="?status=ativos" class="px-3 py-2 rounded-lg text-sm <?= ($statusFiltro == 'ativos') ? 'bg-primary text-white bg-blue-500 px-4 py-2 rounded-lg hover:bg-blue-800' : 'bg-gray-200' ?>">Ativos</a>
                        <a href="?status=inativos" class="px-3 py-2 rounded-lg text-sm <?= ($statusFiltro == 'inativos') ? 'bg-primary text-white bg-blue-500 px-4 py-2 rounded-lg hover:bg-blue-800' : 'bg-gray-200' ?>">Inativos</a>
                    </div>
                </form>

                <a href="cadastroColaborador.php" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black mt-2 md:mt-0">
                    + Novo Colaborador
                </a>

            </div>


            <!-- CARDS RESPONSIVOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($listaPagina as $colab): ?>
                    <div class="p-4 rounded-lg shadow-md flex flex-col md:flex-row md:justify-between gap-4
                    <?= $colab->getStatusColaborador() == 0 ? 'bg-red-50' : 'bg-white' ?>">

                        <!-- Foto e Info -->
                        <div class="flex-1">
                            <img src="<?= $util->montarCaminhoFoto(null, $colab->getIdColaborador()) ?>"
                                class="thumbColab mb-2"
                                onerror="this.onerror=null; this.src='/etreinamento/imagens/user_image.png';">

                            <p class="text-sm text-gray-500">Nome:
                                <span class="font-semibold <?= $colab->getStatusColaborador() == 0 ? 'text-red-600' : '' ?>">
                                    <?= $colab->getNomeColaborador() ?>
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">Empresa:
                                <span class="font-semibold">
                                    <?= $colab->getEmpresaTexto() ?>
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">Cargo: <span class="font-semibold"><?= $colab->getCargo() ?></span></p>
                            <p class="text-sm text-gray-500">Departamento:
                                <span class="font-semibold">
                                    <?= $colab->getDepartamentoTexto() ?>
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">Matrícula: <span class="font-semibold"><?= $colab->getMatriculadoColaborador() ?></span></p>
                            <p class="text-sm text-gray-500">Crachá: <span class="font-semibold"><?= $colab->getCrachaColaborador() ?></span></p>
                        </div>

                        <!-- Ações
                        <div class="flex flex-wrap md:flex-col gap-2 mt-2 md:mt-0 w-full md:w-auto">
                            <button onclick="editar(<?= $colab->getIdColaborador() ?>)"
                                class="bg-yellow-500 text-white px-4 py-2 text-sm rounded hover:bg-yellow-600 transition flex-1 md:flex-none">
                                Editar
                            </button>
                            <button onclick="excluir(<?= $colab->getIdColaborador() ?>)"
                                class="bg-red-500 text-white px-4 py-2 text-sm rounded hover:bg-red-600 transition flex-1 md:flex-none">
                                Excluir
                            </button>
                            <button onclick="status(<?= $colab->getIdColaborador() ?>)"
                                class="<?= $colab->getStatusColaborador() == 1 ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400' ?> 
                               text-white px-4 py-2 text-sm rounded transition flex-1 md:flex-none">
                                Status
                            </button>
                        </div> -->

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- PAGINAÇÃO -->
            <div class="flex justify-center gap-2 mt-6">
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <a href="?pagina=<?= $i ?>&pesquisaColab=<?= urlencode($pesquisaColab ?? '') ?>&status=<?= $statusFiltro ?>"
                        class="px-3 py-1 rounded <?= ($i == $paginaAtual) ? 'bg-primary text-white' : 'bg-gray-200' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

        </main>
    </div>

    <script>
        function editar(id) {
            window.location.href = 'atualizarColaborador.php?idColaborador=' + id;
        }

        function excluir(id) {
            window.location.href = '../src/actions/excluirColaborador.php?idColaborador=' + id;
        }

        function status(id) {
            window.location.href = '../src/actions/alterarStatusColaborador.php?idColaborador=' + id;
        }
    </script>

</body>

</html>