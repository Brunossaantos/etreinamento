<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php
    include(__DIR__ . '/../src/database/conexao.php');
    include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
    include(__DIR__ . '/../src/DAO/DaoInstrutor.php');
    include(__DIR__ . '/../src/Util/Util.php');

    $idTreinamento = $_GET['idTreinamento'];

    $conexao = new Conexao();
    $daoTreinamento = new DaoTreinamento($conexao->conectar());
    $daoInstrutor = new DaoInstrutor($conexao->conectar());
    $util = new Util();

    $treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);

    function nomeDoInstrutor($daoInstrutor, $idInstrutor)
    {
        $instrutor = $daoInstrutor->selecionarInstrutor($idInstrutor);
        return $instrutor->getNomeInstrutor();
    }
    ?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $treinamento->getDescricaoTreinamento() ?></title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div id="menu-container"></div>

    <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow-md">

        <!-- Título -->
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">
            <?php echo $treinamento->getDescricaoTreinamento() ?>
        </h1>

        <!-- Informações -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-sm">

            <div class="bg-gray-50 p-4 rounded-lg border">
                <span class="font-semibold text-gray-600">Data</span>
                <p class="mt-1">
                    <?php echo $util->formatarData($treinamento->getDataTreinamento()) ?>
                </p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border">
                <span class="font-semibold text-gray-600">Instrutor</span>
                <p class="mt-1">
                    <?php echo nomeDoInstrutor($daoInstrutor, $treinamento->getInstrutor()) ?>
                </p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border">
                <span class="font-semibold text-gray-600">Carga Horária</span>
                <p class="mt-1">
                    <?php echo $treinamento->getCargaHoraria() ?>
                </p>
            </div>

        </div>

        <!-- Conteúdo -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-3 text-gray-700 border-b pb-2">
                Conteúdo do treinamento
            </h2>

            <div class="text-gray-700 leading-relaxed text-justify">
                <?php echo $treinamento->getConteudoTreinamento(); ?>
            </div>
        </div>

        <!-- Botão -->
        <div class="text-center">
            <a href="#" id="downloadLink"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-lg transition">
                Baixar material de apoio
            </a>
        </div>

    </div>

</body>

<script>
    fetch('menusuperior.php')
        .then(response => response.text())
        .then(menuHTML => {
            document.getElementById('menu-container').innerHTML = menuHTML;
        })
        .catch(error => {
            console.error('Erro ao carregar o menu:', error);
        });
</script>

<script>
    var idTreinamento = "<?php echo $treinamento->getIdTreinamento(); ?>";
    var extensoesPossiveis = [".pdf", ".docx", ".xlsx", ".png", ".jpg", ".jpeg"];

    var downloadLink = document.getElementById("downloadLink");

    downloadLink.addEventListener("click", function() {
        for (var i = 0; i < extensoesPossiveis.length; i++) {
            var url = "../treinamentos/" + idTreinamento + extensoesPossiveis[i];

            var link = document.createElement("a");
            link.href = url;
            link.style.display = "none";

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });
</script>

</html>