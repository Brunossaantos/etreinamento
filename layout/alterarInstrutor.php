<?php
session_start();

// Validação de sessão (protege a página)
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// Conexão e DAOs
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

// Parâmetro recebido
// ALERTA: Sem validação/sanitização
$idInstrutor = $_GET['idInstrutor'];

// Instancia conexão e DAOs
$conexao = new Conexao();
$daoInstrutor = new DaoInstrutor($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());

// Busca dados do instrutor e lista de departamentos
$instrutor = $daoInstrutor->selecionarInstrutor($idInstrutor);
$listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Instrutor</title>

    <link rel="icon" href="../imagens/favicon.ico">

    <!-- Tailwind via CDN -->
    <!-- ALERTA: CDN em produção pode impactar performance -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonte padrão do sistema -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Configuração visual -->
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

    <!-- Sidebar padrão -->
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <!-- Container principal -->
    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- Header com título -->
        <?php $tituloPagina = "Alterar Instrutor"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- Conteúdo -->
        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-4xl mx-auto">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Imagem ilustrativa -->
                    <div class="flex justify-center items-center">
                        <img
                            src="../imagens/treinamentos/default_treinamentos.jpg"
                            class="h-32 sm:h-40 rounded-lg shadow-sm">
                    </div>

                    <!-- Formulário -->
                    <div class="md:col-span-2">

                        <!-- Envia dados para atualização -->
                        <!-- ALERTA: Uso de GET para update -->
                        <form action="../src/actions/atualizarInstrutor.php" method="get"
                            class="space-y-4">

                            <!-- ID oculto -->
                            <input type="hidden" name="idInstrutor"
                                value="<?= $instrutor->getIdInstrutor() ?>">

                            <!-- Nome -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Nome
                                </label>
                                <input
                                    type="text"
                                    name="nome"
                                    value="<?= $instrutor->getNomeInstrutor() ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 
                                           focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Departamento -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Departamento
                                </label>

                                <!-- Lista dinâmica de departamentos -->
                                <select name="departamento"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 
                                           focus:ring-2 focus:ring-primary">

                                    <?php foreach ($listaDeDepartamentos as $dep) { ?>
                                        <option value="<?= $dep->getIdDepartamento() ?>"
                                            <?= $dep->getIdDepartamento() == $instrutor->getDepartamentoInstrutor() ? 'selected' : '' ?>>
                                            <?= $dep->getNomeDepartamento() ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium mb-2">
                                    Status
                                </label>

                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="1"
                                            <?= $instrutor->getStatusInstrutor() == 1 ? 'checked' : '' ?>>
                                        Ativo
                                    </label>

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="0"
                                            <?= $instrutor->getStatusInstrutor() == 0 ? 'checked' : '' ?>>
                                        Inativo
                                    </label>

                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-4">

                                <button type="submit"
                                    class="w-full sm:w-auto bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                                    Salvar Alterações
                                </button>

                                <!-- Volta sem salvar -->
                                <a href="gerenciarInstrutores.php"
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