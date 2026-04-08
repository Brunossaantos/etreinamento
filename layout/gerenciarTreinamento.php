<?php
session_start();

// 🔐 Validação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// 🔗 Includes
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/Util/Util.php');

// 🔎 Pesquisa
$pesquisaTreinamento = $_GET['pesquisaTreinamento'] ?? null;

// 🔧 Instâncias
$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$util = new Util();

// 🔄 Lista
// 🔄 Lista
$listaTreinamento = empty($pesquisaTreinamento)
    ? $daoTreinamento->gerarListaTreinamentos()
    : $daoTreinamento->pesquisarTreinamento($pesquisaTreinamento);

// 🔄 Ordena do mais recente para o mais antigo
usort($listaTreinamento, function ($a, $b) {
    $dataA = new DateTime($a->getDataTreinamento());
    $dataB = new DateTime($b->getDataTreinamento());
    return $dataB <=> $dataA; // do mais recente para o mais antigo
});

// 🔧 Funções
function nomeInstrutor($daoInstrutor, $idInstrutor)
{
    $instrutor = $daoInstrutor->selecionarInstrutor($idInstrutor);
    return $instrutor ? $instrutor->getNomeInstrutor() : "Não definido";
}

function verificarDataTreinamento($dataTreinamento)
{
    $hoje = new DateTime();
    $hoje->setTime(0, 0, 0);
    $data = new DateTime($dataTreinamento);
    return $data == $hoje;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Treinamentos</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonte -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <!-- Conteúdo -->
    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- HEADER -->
        <?php $tituloPagina = "Gerenciar Treinamentos"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- MAIN -->
        <main class="p-6 flex-1 space-y-6">

            <!-- TOPO: Pesquisa + Botão -->
            <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col md:flex-row md:justify-between gap-4">

                <form method="GET" class="flex gap-2 flex-wrap w-full md:w-auto">
                    <input type="text" name="pesquisaTreinamento"
                        value="<?= htmlspecialchars($pesquisaTreinamento ?? '') ?>"
                        placeholder="Pesquisar treinamento..."
                        class="border border-gray-300 rounded-lg px-3 py-2 w-full md:w-72 focus:ring-2 focus:ring-primary">

                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                        Buscar
                    </button>
                </form>

                <a href="cadastroDeTreinamentos.php"
                    class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-black transition mt-2 md:mt-0">
                    + Novo Treinamento
                </a>

            </div>

            <!-- CARDS RESPONSIVOS -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">

                <?php foreach ($listaTreinamento as $treinamento):
                    $ativo = verificarDataTreinamento($treinamento->getDataTreinamento()) ? 1 : 0;
                ?>
                    <div class="bg-white p-4 rounded-lg shadow-md flex flex-col md:flex-row md:justify-between gap-4">

                        <!-- Informações -->
                        <div class="flex-1">
                            <p class="text-sm text-gray-500">Prioridade: <span class="font-semibold"><?= $ativo ?></span></p>
                            <p class="text-sm text-gray-500">Data: <span class="font-semibold"><?= $util->formatarData($treinamento->getDataTreinamento()) ?></span></p>
                            <p class="text-sm text-gray-500">Descrição:
                                <a href="visualizarTreinamento.php?idTreinamento=<?= $treinamento->getIdTreinamento() ?>"
                                    class="text-primary font-semibold hover:underline">
                                    <?= $treinamento->getDescricaoTreinamento(); ?>
                                </a>
                            </p>
                            <p class="text-sm text-gray-500">Instrutor: <span class="font-semibold"><?= nomeInstrutor($daoInstrutor, $treinamento->getInstrutor()); ?></span></p>
                            <p class="text-sm text-gray-500">Carga: <span class="font-semibold"><?= $treinamento->getCargaHoraria() ?></span></p>
                        </div>

                        <!-- Ações -->
                        <div class="flex gap-2 mt-2 md:mt-0 w-full md:w-auto md:flex-col">

                            <button onclick="alterar(<?= $treinamento->getIdTreinamento() ?>)"
                                class="flex items-center justify-center bg-yellow-500 text-white px-3 py-2 text-xs md:text-sm rounded-lg hover:bg-yellow-600 transition w-full md:w-36">
                                Editar
                            </button>

                            <button onclick="excluir(<?= $treinamento->getIdTreinamento() ?>)"
                                class="flex items-center justify-center bg-red-500 text-white px-3 py-2 text-xs md:text-sm rounded-lg hover:bg-red-600 transition w-full md:w-36">
                                Cancelar
                            </button>

                            <?php if ($ativo): ?>
                                <button onclick="iniciar(<?= $treinamento->getIdTreinamento() ?>)"
                                    class="flex items-center justify-center bg-green-600 text-white px-3 py-2 text-xs md:text-sm rounded-lg hover:bg-green-700 transition w-full md:w-36">
                                    Iniciar
                                </button>
                            <?php else: ?>
                                <button
                                    class="flex items-center justify-center bg-gray-400 text-white px-3 py-2 text-xs md:text-sm rounded-lg w-full md:w-36 cursor-not-allowed">
                                    Encerrado
                                </button>
                            <?php endif; ?>

                            <button onclick="lista(<?= $treinamento->getIdTreinamento() ?>)"
                                class="flex items-center justify-center bg-primary text-white px-3 py-2 text-xs md:text-sm rounded-lg hover:bg-blue-800 transition w-full md:w-36">
                                Lista
                            </button>

                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

        </main>
    </div>

    <script>
        function excluir(id) {
            window.location.href = '../src/actions/excluirTreinamento.php?idTreinamento=' + id;
        }

        function alterar(id) {
            window.location.href = '../layout/alterarTreinamento.php?idTreinamento=' + id;
        }

        function iniciar(id) {
            window.location.href = '../layout/listaDePresenca.php?idTreinamento=' + id;
        }

        function lista(id) {
            window.location.href = '../layout/gerarListaDePresenca.php?idTreinamento=' + id;
        }

        $(document).ready(function() {
            $('#tabelaTreinamentos').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'desc'],
                    [1, 'desc']
                ],
                columnDefs: [{
                    targets: 0,
                    visible: false
                }],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                }
            });
        });
    </script>

</body>

</html>