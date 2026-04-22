<?php
session_start();

// Proteção de rota: impede acesso sem autenticação
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

// Conexão com o banco de dados principal do sistema
include(__DIR__ . '/../src/database/conexao.php');

// DAO responsável pelas operações de Departamento
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

// Instancia conexão e DAO para uso na tela
$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Departamento</title>

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
        <?php $tituloPagina = "Cadastrar Departamento"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-xl mx-auto">

                <!-- Formulário de cadastro de departamento -->
                <form action="../src/actions/cadastrarDepartamento.php"
                    method="get"
                    class="space-y-4">

                    <!-- Nome do departamento -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Nome do Departamento
                        </label>
                        <input type="text" name="departamento"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary"
                            placeholder="Digite o nome do departamento"
                            required>
                    </div>

                    <!-- Status do departamento -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Status
                        </label>

                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">

                            <label class="flex items-center gap-2">
                                <input type="radio" name="status" value="1" checked>
                                Ativo
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio" name="status" value="0">
                                Inativo
                            </label>

                        </div>
                    </div>

                    <!-- Botões de ação -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">

                        <button type="submit"
                            class="w-full sm:w-auto bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                            Cadastrar
                        </button>

                        <a href="gerenciarDepartamentos.php"
                            class="w-full sm:w-auto text-center bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400 transition">
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </main>
    </div>

</body>

</html>