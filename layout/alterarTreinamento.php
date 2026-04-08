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
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

$idTreinamento = $_GET['idTreinamento'];

$conexao = new Conexao();
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
$listaDeInstrutores = $daoInstrutor->gerarListaInstrurores();
$listaDepartamentos = $daoDepartamento->gerarListaDepartamentos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Treinamento</title>

    <link rel="icon" href="../imagens/favicon.ico">

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
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <!-- Conteúdo -->
    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- HEADER -->
        <?php $tituloPagina = "Alterar Treinamento"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- MAIN -->
        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-5xl mx-auto">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- IMAGEM -->
                    <div class="flex justify-center items-start">
                        <img
                            src="../imagens/treinamentos/default_treinamentos.jpg"
                            class="h-32 sm:h-40 rounded-lg shadow-sm">
                    </div>

                    <!-- FORM -->
                    <div class="md:col-span-2">

                        <form action="../src/actions/alterarTreinamento.php" method="get"
                            class="space-y-4">

                            <input type="hidden" name="idTreinamento"
                                value="<?= $treinamento->getIdTreinamento() ?>">

                            <!-- Descrição -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Descrição</label>
                                <input type="text" name="descricao"
                                    value="<?= $treinamento->getDescricaoTreinamento() ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Conteúdo -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Conteúdo</label>
                                <textarea name="conteudo" rows="4"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"><?= $treinamento->getConteudoTreinamento() ?></textarea>
                            </div>

                            <!-- GRID -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm font-medium mb-1">Carga horária</label>
                                    <input type="time" name="cargaHoraria"
                                        value="<?= $treinamento->getCargaHoraria() ?>"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Data</label>
                                    <input type="date" name="data"
                                        value="<?= $treinamento->getDataTreinamento() ?>"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                                </div>

                            </div>

                            <!-- Instrutor -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Instrutor</label>

                                <select name="instrutor"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">

                                    <?php foreach ($listaDeInstrutores as $instrutor) { ?>
                                        <option value="<?= $instrutor->getIdInstrutor() ?>"
                                            <?= $instrutor->getIdInstrutor() == $treinamento->getInstrutor() ? 'selected' : '' ?>>
                                            <?= $instrutor->getNomeInstrutor() ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- Local -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Local</label>
                                <input type="text" name="local"
                                    value="<?= $treinamento->getLocalTreinamento() ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Departamento -->
                            <div>
                                <label class="block text-sm font-medium mb-1">Departamento</label>

                                <select name="departamento"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">

                                    <?php foreach ($listaDepartamentos as $dep) { ?>
                                        <option value="<?= $dep->getIdDepartamento() ?>"
                                            <?= $dep->getIdDepartamento() == $treinamento->getDepartamento() ? 'selected' : '' ?>>
                                            <?= $dep->getNomeDepartamento() ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium mb-2">Status</label>

                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="1"
                                            <?= $treinamento->getStatusTreinamento() == 1 ? 'checked' : '' ?>>
                                        Ativo
                                    </label>

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="0"
                                            <?= $treinamento->getStatusTreinamento() == 0 ? 'checked' : '' ?>>
                                        Inativo
                                    </label>
                                </div>
                            </div>

                            <!-- BOTÕES -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-4">

                                <button type="submit"
                                    class="w-full sm:w-auto bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                                    Salvar Alterações
                                </button>

                                <a href="gerenciarTreinamento.php"
                                    class="w-full sm:w-auto text-center bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                                    Cancelar
                                </a>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </main>
    </div>

</body>

</html>