<?php
session_start();

// Proteção de rota: impede acesso sem autenticação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// Conexão com o banco de dados principal do sistema
include(__DIR__ . '/../src/database/conexao.php');

// DAO responsável pelas operações da entidade Empresa
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');

// Utilitário responsável por funções auxiliares (ex: caminhos de arquivos)
include(__DIR__ . '/../src/Util/Util.php');

// Instancia conexão e DAO para acesso aos dados
$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$util = new Util();

// ID da empresa recebido via GET para busca do registro
$idEmpresa = $_GET['idEmpresa'];

// Consulta dados da empresa no banco
$empresa = $daoEmpresa->selecionarEmpresa($idEmpresa);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Empresa</title>

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

    <!-- Sidebar do sistema -->
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- Título da página -->
        <?php $tituloPagina = "Atualizar Empresa"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-4xl mx-auto">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Exibição do logotipo da empresa -->
                    <div class="flex flex-col items-center gap-4">

                        <img
                            src="<?= $util->montarCaminhoLogotipo(null, $empresa->getIdEmpresa()) ?>"
                            class="h-28 sm:h-32 rounded-lg shadow object-contain"
                            onerror="this.src='../imagens/sem-logo.png'">

                        <span class="text-sm text-gray-500 text-center">
                            <?= $empresa->getNomeEmpresa() ?>
                        </span>

                    </div>

                    <!-- Formulário de atualização -->
                    <div class="md:col-span-2">

                        <form action="../src/actions/processarAtualizacaoEmpresa.php"
                            method="post"
                            enctype="multipart/form-data"
                            class="space-y-4">

                            <!-- ID oculto da empresa -->
                            <input type="hidden" name="idEmpresa"
                                value="<?= $empresa->getIdEmpresa() ?>">

                            <!-- Upload de logotipo -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Logotipo da empresa
                                </label>
                                <input type="file" name="logo"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            </div>

                            <!-- Nome da empresa -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Nome da empresa
                                </label>
                                <input type="text" name="nome"
                                    value="<?= $empresa->getNomeEmpresa() ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Status da empresa -->
                            <div>
                                <label class="block text-sm font-medium mb-2">
                                    Status
                                </label>

                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="1"
                                            <?= $empresa->getStatusEmpresa() == 1 ? 'checked' : '' ?>>
                                        Ativo
                                    </label>

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="0"
                                            <?= $empresa->getStatusEmpresa() == 0 ? 'checked' : '' ?>>
                                        Inativo
                                    </label>

                                </div>
                            </div>

                            <!-- Botões de ação -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-4">

                                <button type="submit"
                                    class="w-full sm:w-auto bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                                    Salvar Alterações
                                </button>

                                <a href="gerenciarEmpresas.php"
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