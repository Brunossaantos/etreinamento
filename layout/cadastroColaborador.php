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
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');

$conexao = new Conexao();
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$daoDepartamento = new DaoDepartamento($conexao->conectar());

$listaDeEmpresas = $daoEmpresa->gerarListaEmpresas();
$listaDepartamentos = $daoDepartamento->gerarListaDepartamentos();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Colaborador</title>

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
    <div class="flex flex-col min-h-screen lg:ml-64">

        <!-- Header -->
        <?php $tituloPagina = "Cadastrar Colaborador"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- Main -->
        <main class="p-4 sm:p-6 flex-1">

            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 max-w-5xl mx-auto w-full">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">

                    <!-- IMAGEM -->
                    <div class="flex justify-center md:justify-start">
                        <img src="../imagens/colaboradores/user_image.png"
                            class="rounded-lg shadow-md max-h-32 sm:max-h-40">
                    </div>

                    <!-- FORM -->
                    <div class="md:col-span-2">

                        <form action="../src/actions/cadastroColaborador.php"
                            method="post"
                            enctype="multipart/form-data"
                            class="space-y-4">

                            <div>
                                <label class="block text-sm mb-1">Foto do Colaborador</label>
                                <input type="file" name="foto"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Nome</label>
                                <input type="text" name="nome"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Matrícula</label>
                                <input type="text" name="matricula" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Cargo</label>
                                <input type="text" name="cargo"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Departamento</label>
                                <select name="departamento"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <?php foreach ($listaDepartamentos as $departamento) {
                                        if ($departamento->getStatusDepartamento() == 1) { ?>
                                            <option value="<?= $departamento->getIdDepartamento() ?>">
                                                <?= $departamento->getNomeDepartamento() ?>
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Empresa</label>
                                <select name="empresa"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <?php foreach ($listaDeEmpresas as $empresa) {
                                        if ($empresa->getStatusEmpresa() == 1) { ?>
                                            <option value="<?= $empresa->getIdEmpresa() ?>">
                                                <?= $empresa->getNomeEmpresa() ?>
                                            </option>
                                    <?php }
                                    } ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm mb-1">Crachá</label>
                                <input type="text" name="cracha" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            </div>

                            <!-- BOTÕES -->
                            <div class="flex flex-col sm:flex-row gap-3 pt-4">

                                <button type="submit"
                                    class="w-full sm:w-auto bg-primary text-white px-5 py-2 rounded-lg hover:bg-blue-800 transition">
                                    Salvar
                                </button>

                                <a href="gerenciarColaboradores.php"
                                    class="w-full sm:w-auto text-center bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-700 transition">
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