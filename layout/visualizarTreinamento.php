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
    <title>
        <?php echo $treinamento->getDescricaoTreinamento() ?>
    </title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
</head>

<body>
    <div id="menu-container"><!-- O menu esta sendo carregado aqui  --></div>
    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        <div class="text-center">
                        <?php echo $treinamento->getDescricaoTreinamento() ?>
                        </div>                        
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table">
            <thead>
                <tr>
                    <th>Data do treinamento</th>                    
                    <th>Instrutor</th>
                    <th>Carga Horária do treinamento</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo $util->formatarData($treinamento->getDataTreinamento()) ?>
                    </td>                    
                    <td>
                        <?php echo nomeDoInstrutor($daoInstrutor, $treinamento->getInstrutor()) ?>
                    </td>
                    <td>
                        <?php echo $treinamento->getCargaHoraria()?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th>Conteúdo do treinamento</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="text-justify">
                            <?php echo $treinamento->getConteudoTreinamento(); ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="container mt-5">
            <a href="#" id="downloadLink" class="btn btn-primary">Download do material de apoio do treinamento</a>
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
    downloadLink.addEventListener("click", function () {
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