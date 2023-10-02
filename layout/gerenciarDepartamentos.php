<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION["user_id"])) {
    // O usuário não está logado, redirecione para a página de login
    header("Location: ../index.php");
    exit();
}

// O usuário está logado, continue exibindo o conteúdo da página protegida
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de departamentos</title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
    <!-- Inclua o link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../estilo/estilo.css"> -->
    <style>
        .thumbColad,
        img {
            height: 100px;
            width: auto;
            border-radius: 10px;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<?php
include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoColaborador.php');

$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());
// $listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

// Carregue todos os departamentos se não houver pesquisa
if (empty($pesquisa)) {
    $listaDeDepartamentos = $daoDepartamento->gerarListaDepartamentos();
} else {
    // Se houver pesquisa, filtre os departamentos
    $listaDeDepartamentos = $daoDepartamento->pesquisarDepartamentos($pesquisa);
}


function nomeDepartamento($daoDepartamento, $idDepartamento)
{
    $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);

    if ($departamento != null) {
        return $departamento->getNomeDepartamento();
    } else {
        return "Departamento não cadastrado";
    }
}

function statusDepartamento($daoDepartamento, $idDepartamento)
{
    $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);

    if ($departamento === null) {
        return "Inativo";
    }

    return $departamento->getStatusDepartamento() == 1 ? "Ativo" : "Inativo";
}


?>

<body>
    <div id="menu-container">
        <!-- O menu será carregado aqui -->
    </div>
    <div class="container mt-5">
        <h1>Gerenciador de departamentos</h1>
        <!-- Formulário de Pesquisa -->
        <form method="GET">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Pesquisar departamentos" name="pesquisa"
                        id="pesquisa">
                </div>
                <div class="col-md-2 btn-group">
                    <button type="submit" class="btn btn-primary">Pesquisar</button>
                </div>
                <div class="col-md-2">
                    <a href="cadastrarDepartamento.php" class="btn btn-success">Cadastrar departamento</a>
                </div>
            </div>
        </form>

        <!-- Tabela de Treinamentos -->
        <table class="table">
            <thead>
                <tr>
                    <th>Departamento</th>
                    <th>Status</th>
                    <th>Qtde colaboradores<br>cadastrados no departamento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Exemplo de uma linha na tabela (pode adicionar mais linhas dinamicamente) -->
                <?php foreach ($listaDeDepartamentos as $departamento) { ?>
                    <tr>
                        <td>
                            <?php echo $departamento->getNomeDepartamento() ?>
                        </td>
                        <td>
                            <?php echo statusDepartamento($daoDepartamento, $departamento->getIdDepartamento()) ?>
                        </td>
                        <td id="qtdColab">
                            <?php echo $daoDepartamento->contarColab($departamento->getIdDepartamento()) ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-danger" value=""
                                    onclick="redirecionarParaExcluirDepartamento('<?php echo $departamento->getIdDepartamento() ?>')">Excluir</button>
                                <button class="btn btn-warning" value=""
                                    onclick="redirecionarParaAtualizarDepartamento('<?php echo $departamento->getIdDepartamento() ?>')">Atualizar</button>
                                <?php if ($departamento->getStatusDepartamento() == 1) { ?>
                                    <button class="btn btn-success" value=""
                                        onclick="redirecionarParaAlterarStatusDepartamento('<?php echo $departamento->getIdDepartamento() ?>')">Status</button>
                                <?php } else { ?>
                                    <button class="btn btn-secondary" value=""
                                        onclick="redirecionarParaAlterarStatusDepartamento('<?php echo $departamento->getIdDepartamento() ?>')">Status</button>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script>
        function redirecionarParaAtualizarDepartamento(idDepartamento) {
            window.location.href = 'alterarDepartamento.php?idDepartamento=' + idDepartamento;
        }

        function redirecionarParaExcluirDepartamento(idDepartamento) {
            var qtdColab = parseInt(document.getElementById("qtdColab").textContent);           

            if (qtdColab != 0) {
                alert("Antes de excluir o departamento é necessário mover todos os colaboradores para outro departamento");
            } else {
                window.location.href = '../src/actions/excluirDepartamento.php?idDepartamento=' + idDepartamento;
            }
        }


        function redirecionarParaAlterarStatusDepartamento(idDepartamento) {
            window.location.href = '../src/actions/alterarStatusDepartamento.php?idDepartamento=' + idDepartamento;
        }

    </script>
    <!-- Inclua os scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
</body>
<script>
    // Use JavaScript para carregar o conteúdo do menu.html no elemento com o ID "menu-container"
    fetch('menusuperior.php')
        .then(response => response.text())
        .then(menuHTML => {
            document.getElementById('menu-container').innerHTML = menuHTML;
        })
        .catch(error => {
            console.error('Erro ao carregar o menu:', error);
        });
</script>

</html>