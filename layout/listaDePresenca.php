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
    <title>Lista de presença
        <?php echo "- Tag para descrição do treinamento"; ?>
    </title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../estilo/estilo.css"> -->
    <style>
        .colaborador {
            height: 180px;
            border-radius: 20px;
        }
    </style>
</head>

<body>
    <?php
    include(__DIR__ . '/../src/database/conexao.php');
    include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
    include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
    include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
    include(__DIR__ . '/../src/Util/util.php');

    $idTreinamento = $_GET['idTreinamento'];    

    $conexao = new Conexao();
    $daoDepartamento = new DaoDepartamento($conexao->conectar());
    $daoEmpresa = new DaoEmpresa($conexao->conectar());
    $daoTreinamento = new DaoTreinamento($conexao->conectar());
    $util = new Util();

    $treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);


    if (isset($_GET['nome']) && isset($_GET['matricula']) && isset($_GET['cargo']) && isset($_GET['departamento']) && isset($_GET['empresa']) && isset($_GET['hexadecimal'])) {
        $nome = $_GET['nome'];
        $matricula = $_GET['matricula'];
        $cargo = $_GET['cargo'];
        $departamento = $_GET['departamento'];
        $empresa = $_GET['empresa'];
        //$cracha = $_GET['hexadecimal'];
    } else {
        $nome = "";
        $matricula = "";
        $cargo = "";
        $departamento = "";
        $empresa = "";
        $cracha = "";
    }

    function recuperarNomeDepto($daoDepartamento ,$idDepartamento){  
        if($idDepartamento != null){      
            $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);
            return $departamento->getNomeDepartamento();
        } else {
            return "Departamento não encontrado"; 
        }        
    }

    function recuperarNomeEmpresa($daoEmpresa,$idEmpresa){
        if($idEmpresa != null){
            $empresa = $daoEmpresa->selecionarEmpresa($idEmpresa);
            return $empresa->getNomeEmpresa();
        } else {
            return "Empresa não cadastrada";
        }
    }
    
    ?>

<div class="container">
    <h1 id="titulo" class="mb-4 mx-auto"><?php echo $treinamento->getDescricaoTreinamento();?></h1>
    <form action="../src/actions/inserirPresenca.php" method="get" enctype="multipart/form-data">
        <input type="hidden" name="idTreinamento" id="idTreinamento" value="<?php echo $idTreinamento?>">
        <div class="mb-3">
            <img src="<?php echo $util->montarCaminhoFoto("../imagens/colaboradores/", $matricula); ?>" alt="<?php echo $nome; ?>"
                class="colaborador mx-auto">
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" value="<?php echo $nome ?>" class="form-control" name="nome" id="nome"
                    placeholder="Nome" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="matricula" class="form-label">Matrícula:</label>
                <input type="text" value="<?php echo $matricula ?>" class="form-control" name="matricula"
                    id="matricula" placeholder="Matricula" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="cargo" class="form-label">Cargo:</label>
                <input type="text" value="<?php echo $cargo ?>" class="form-control" name="cargo" id="cargo"
                    placeholder="Cargo" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="departamento" class="form-label">Departamento:</label>
                <input type="text" value="<?php echo recuperarNomeDepto($daoDepartamento, $departamento); ?>"
                    class="form-control" name="departamento" id="departamento" placeholder="Departamento" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="empresa" class="form-label">Empresa:</label>
                <input type="text" value="<?php echo recuperarNomeEmpresa($daoEmpresa, $empresa) ?>" class="form-control" name="empresa"
                    id="empresa" placeholder="Empresa" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="hexadecimal" class="form-label">Crachá:</label>
                <input type="text" autofocus value="" class="form-control"
                    name="hexadecimal" id="hexadecimal" pattern="[0-9A-Fa-f]{10}"
                    title="Deve ser um valor hexadecimal de 10 dígitos (0-9, A-F ou a-f)"
                    placeholder="Numero do cracha" required autocomplete="off">
            </div>
        </div>
            <div class="btn btn-group">
                <button type="submit" class="btn btn-primary">Confirma presença</button>
                <!-- <button type="submit" class="btn btn-danger mr-2">Encerrar a lista de presença</button> -->
            </div>
    </form>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
</body>

</html>