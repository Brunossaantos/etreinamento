<body class="bg-gray-100 font-sans text-gray-800">

    <!-- Sidebar padrão do sistema -->
    <?php include(__DIR__ . '/../src/Util/sidebar.php'); ?>

    <!-- Container principal -->
    <div class="flex flex-col md:ml-64 min-h-screen">

        <!-- Header com título dinâmico -->
        <?php $tituloPagina = "Alterar Departamento"; ?>
        <?php include(__DIR__ . '/../src/Util/header.php'); ?>

        <!-- Conteúdo principal -->
        <main class="p-4 sm:p-6 flex-1">

            <!-- Card central -->
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
                        <!-- ALERTA: Uso de GET para update (não recomendado) -->
                        <form action="../src/actions/atualizarDepartamento.php" method="get"
                            class="space-y-4">

                            <!-- ID oculto do departamento -->
                            <input type="hidden" name="idDepartamento"
                                value="<?= $departamento->getIdDepartamento() ?>">

                            <!-- Nome do departamento -->
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Departamento
                                </label>
                                <input
                                    type="text"
                                    name="departamento"
                                    value="<?= $departamento->getNomeDepartamento() ?>"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 
                                           focus:ring-2 focus:ring-primary">
                            </div>

                            <!-- Status (ativo/inativo) -->
                            <div>
                                <label class="block text-sm font-medium mb-2">
                                    Status
                                </label>

                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="1"
                                            <?= $departamento->getStatusDepartamento() == 1 ? 'checked' : '' ?>>
                                        Ativo
                                    </label>

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="status" value="0"
                                            <?= $departamento->getStatusDepartamento() == 0 ? 'checked' : '' ?>>
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

                                <!-- Retorna sem salvar -->
                                <a href="gerenciarDepartamentos.php"
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