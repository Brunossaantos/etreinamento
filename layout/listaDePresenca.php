<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/Util/util.php');

$idTreinamento = $_GET['idTreinamento'] ?? null;

$conexao = new Conexao();
$daoDepartamento = new DaoDepartamento($conexao->conectar());
$daoEmpresa = new DaoEmpresa($conexao->conectar());
$daoTreinamento = new DaoTreinamento($conexao->conectar());
$util = new Util();

// Recupera o treinamento
$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
if ($treinamento === null) {
    die("Treinamento não encontrado ou ID inválido.");
}

// Inicializa variáveis dos campos (vindo do GET caso exista)
$nome = $_GET['nome'] ?? "";
$matricula = $_GET['matricula'] ?? "";
$cargo = $_GET['cargo'] ?? "";
$departamento = $_GET['departamento'] ?? "";
$empresa = $_GET['empresa'] ?? "";
$cracha = $_GET['hexadecimal'] ?? "";

// Funções auxiliares
function recuperarNomeDepto($daoDepartamento, $idDepartamento)
{
    if ($idDepartamento != null) {
        $departamento = $daoDepartamento->selecionarDepartamento($idDepartamento);
        return $departamento ? $departamento->getNomeDepartamento() : "Departamento não encontrado";
    }
    return "Departamento não encontrado";
}

function recuperarNomeEmpresa($daoEmpresa, $idEmpresa)
{
    if ($idEmpresa != null) {
        $empresa = $daoEmpresa->selecionarEmpresa($idEmpresa);
        return $empresa ? $empresa->getNomeEmpresa() : "Empresa não cadastrada";
    }
    return "Empresa não cadastrada";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de presença - <?php echo htmlspecialchars($treinamento->getDescricaoTreinamento()); ?></title>
    <link rel="icon" href="../imagens/favicon.ico" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg">
        <h1 class="text-2xl font-bold text-blue-600 text-center mb-6"><?php echo htmlspecialchars($treinamento->getDescricaoTreinamento()); ?></h1>

        <form action="../src/actions/inserirPresenca.php" method="get" enctype="multipart/form-data">
            <input type="hidden" name="idTreinamento" value="<?php echo $idTreinamento; ?>">

            <div class="flex justify-center mb-6">
                <img src="<?php echo $util->montarCaminhoFoto("../imagens/colaboradores/", $matricula); ?>"
                    alt="<?php echo htmlspecialchars($nome); ?>"
                    class="w-44 h-44 object-cover rounded-full border-4 border-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nome:</label>
                    <input type="text" value="<?php echo htmlspecialchars($nome); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        name="nome" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Matrícula:</label>
                    <input type="text" value="<?php echo htmlspecialchars($matricula); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        name="matricula" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Cargo:</label>
                    <input type="text" value="<?php echo htmlspecialchars($cargo); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        name="cargo" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Departamento:</label>
                    <input type="text" value="<?php echo htmlspecialchars(recuperarNomeDepto($daoDepartamento, $departamento)); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        name="departamento" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Empresa:</label>
                    <input type="text" value="<?php echo htmlspecialchars(recuperarNomeEmpresa($daoEmpresa, $empresa)); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        name="empresa" readonly>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Crachá:</label>
                    <input type="text" autofocus value="<?php echo htmlspecialchars($cracha); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                        name="hexadecimal" pattern="[0-9A-Fa-f]{10}"
                        title="Deve ser um valor hexadecimal de 10 dígitos (0-9, A-F ou a-f)"
                        placeholder="Número do crachá" required autocomplete="off">
                </div>
            </div>

            <div class="flex justify-center mt-6">
                <button type="submit"
                    class="bg-blue-600 text-white px-8 py-3 rounded-xl hover:bg-blue-700 transition-all font-semibold">
                    Confirma presença
                </button>
            </div>
        </form>
    </div>

</body>

</html>