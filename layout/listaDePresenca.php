<?php
session_start();

// 🔐 Validação de sessão
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

include(__DIR__ . '/../src/database/conexao.php');
include(__DIR__ . '/../src/DAO/DaoDepartamento.php');
include(__DIR__ . '/../src/DAO/DaoEmpresa.php');
include(__DIR__ . '/../src/DAO/DaoTreinamento.php');
include(__DIR__ . '/../src/Util/Util.php');

$idTreinamento = $_GET['idTreinamento'] ?? null;

// 🔌 Conexão
$conexao = new Conexao();
$conn = $conexao->conectar();

$daoDepartamento = new DaoDepartamento($conn);
$daoEmpresa = new DaoEmpresa($conn);
$daoTreinamento = new DaoTreinamento($conn);
$util = new Util();

// 🔎 Recupera treinamento
$treinamento = $daoTreinamento->selecionarTreinamento($idTreinamento);
if ($treinamento === null) {
    die("Treinamento não encontrado ou ID inválido.");
}

// 🧼 Limpeza
function limpar($valor)
{
    return ($valor === "null" || $valor === null) ? "" : $valor;
}

// 📥 Dados
$nome = limpar($_GET['nome'] ?? "");
$matricula = limpar($_GET['matricula'] ?? "");
$cargo = limpar($_GET['cargo'] ?? "");
$departamento = limpar($_GET['departamento'] ?? "");
$empresa = limpar($_GET['empresa'] ?? "");

// 🔎 Auxiliares
/*function recuperarNomeDepto($daoDepartamento, $idDepartamento)
{
    if (!empty($idDepartamento)) {
        $d = $daoDepartamento->selecionarDepartamento($idDepartamento);
        return $d ? $d->getNomeDepartamento() : "";
    }
    return "";
}

function recuperarNomeEmpresa($daoEmpresa, $idEmpresa)
{
    if (!empty($idEmpresa)) {
        $e = $daoEmpresa->selecionarEmpresa($idEmpresa);
        return $e ? $e->getNomeEmpresa() : "";
    }
    return "";
}*/

$idColaborador = $_GET['idColaborador'] ?? null;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <!-- 🔒 viewport travado -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Lista de presença - <?= htmlspecialchars($treinamento->getDescricaoTreinamento()); ?></title>
    <link rel="icon" href="../imagens/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex justify-center items-start pt-24 px-4">

    <!-- 🔝 HEADER -->
    <div class="w-full bg-white/30 backdrop-blur-md py-3 md:py-4 px-4 flex justify-center items-center gap-4 md:gap-8 fixed top-0 left-0 z-50">

        <a href="gerenciarTreinamento.php"
            class="bg-gray-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-xl text-sm md:text-base font-semibold">
            ← Voltar
        </a>

        <a href="../index2.php"
            class="bg-blue-600 text-white px-4 md:px-6 py-2 md:py-3 rounded-xl text-sm md:text-base font-semibold">
            Home
        </a>

    </div>

    <!-- 📦 CARD -->
    <div class="bg-white p-5 md:p-6 rounded-2xl shadow-lg w-full max-w-md md:max-w-2xl lg:max-w-3xl">

        <!-- ✅ SUCESSOS -->
        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'presenca_registrada'): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                Presença registrada com sucesso ✅
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'cracha_vinculado'): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                Crachá vinculado com sucesso ✅
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'cracha_transferido'): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                Crachá transferido com sucesso 🔄
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'sem_cracha'): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 text-center">
                Colaborador cadastrado sem crachá ⚠️
            </div>
        <?php endif; ?>

        <!-- 🔥 BOTÃO SEM CRACHÁ (AGORA SEMPRE VISÍVEL) -->
        <?php if (!empty($idTreinamento)): ?>
            <div class="flex justify-center mb-4">
                <a href="cadastroColaborador.php?idTreinamento=<?= $idTreinamento ?>&hexadecimal=0"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Registrar sem crachá
                </a>
            </div>
        <?php endif; ?>
        <!-- 🔎 BUSCA POR NOME -->
        <div class="mb-4">
            <input type="text" id="buscaNome"
                placeholder="Buscar colaborador pelo nome..."
                class="w-full px-4 py-3 border rounded-lg">

            <div id="resultadoBusca" class="mt-2 space-y-2"></div>
        </div>

        <!-- ❌ ERROS -->

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'presenca_duplicada'): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 text-center">
                Esse colaborador já registrou presença ⚠️
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'cracha_nao_encontrado'): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-center">
                <p class="font-semibold">Crachá não encontrado</p>

                <div class="mt-4 flex flex-col md:flex-row gap-3 justify-center">
                    <a href="vincularCracha.php?idTreinamento=<?= $idTreinamento ?>&hexadecimal=<?= urlencode($_GET['hexadecimal']) ?>"
                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-center">
                        Vincular a colaborador
                    </a>

                    <a href="cadastroColaborador.php?hexadecimal=<?= urlencode($_GET['hexadecimal']) ?>&idTreinamento=<?= $idTreinamento ?>"
                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-center">
                        Criar novo colaborador
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['erro']) && $_GET['erro'] == 'cracha_duplicado'): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 text-center">

                <p class="font-semibold">
                    Esse crachá já está vinculado a <?= htmlspecialchars($_GET['nomeExistente'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </p>

                <div class="mt-4 flex gap-3 justify-center flex-col md:flex-row">

                    <a href="../src/actions/transferirCracha.php
                ?idTreinamento=<?= $idTreinamento ?>
                &idNovo=<?= $_GET['idNovo'] ?>
                &hexadecimal=<?= $_GET['hexadecimal'] ?>"
                        class="bg-orange-500 text-white px-4 py-2 rounded-lg text-center">

                        Transferir crachá
                    </a>

                    <a href="listaDePresenca.php?idTreinamento=<?= $idTreinamento ?>"
                        class="bg-gray-400 text-white px-4 py-2 rounded-lg text-center">
                        Cancelar
                    </a>

                </div>
            </div>
        <?php endif; ?>

        <!-- 📌 TÍTULO -->
        <h1 class="text-xl md:text-2xl font-bold text-blue-600 text-center mb-6">
            <?= htmlspecialchars($treinamento->getDescricaoTreinamento()); ?>
        </h1>

        <!-- 📋 FORM -->
        <form id="formPresenca" action="../src/actions/inserirPresenca.php" method="get">
            <input type="hidden" name="idTreinamento" value="<?= $idTreinamento ?>">

            <!-- 🖼️ FOTO -->
            <div class="flex justify-center mb-6">
                <img src="<?= $util->montarCaminhoFoto(null, $idColaborador) ?>"
                    class="w-32 h-32 md:w-40 md:h-40 object-cover rounded-full border-4 border-blue-500"
                    onerror="this.onerror=null; this.src='/etreinamento/imagens/user_image.png';">
            </div>

            <!-- 📊 DADOS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="font-semibold">Nome:</label>
                    <input type="text" value="<?= htmlspecialchars($nome) ?>" class="w-full px-4 py-2 border rounded-lg" readonly>
                </div>

                <div>
                    <label class="font-semibold">Matrícula:</label>
                    <input type="text" value="<?= htmlspecialchars($matricula) ?>" class="w-full px-4 py-2 border rounded-lg" readonly>
                </div>

                <div>
                    <label class="font-semibold">Cargo:</label>
                    <input type="text" value="<?= htmlspecialchars($cargo) ?>" class="w-full px-4 py-2 border rounded-lg" readonly>
                </div>

                <div>
                    <label class="font-semibold">Departamento:</label>
                    <input type="text" value="<?= htmlspecialchars($departamento) ?>" class="w-full px-4 py-2 border rounded-lg" readonly>
                </div>

                <div>
                    <label class="font-semibold">Empresa:</label>
                    <input type="text" value="<?= htmlspecialchars($empresa) ?>" class="w-full px-4 py-2 border rounded-lg" readonly>
                </div>

                <!-- 🔥 INPUT -->
                <div class="md:col-span-2">
                    <label class="font-semibold">Aproxime o crachá:</label>
                    <input type="text"
                        id="crachaInput"
                        name="hexadecimal"
                        class="w-full px-4 py-4 border-2 border-blue-600 rounded-xl text-center text-xl md:text-2xl font-bold tracking-widest"
                        autofocus
                        autocomplete="off"
                        pattern="[0-9A-Fa-f]{10}"
                        required>
                </div>

            </div>
        </form>

    </div>

    <script>
        const input = document.getElementById('crachaInput');
        const form = document.getElementById('formPresenca');

        let enviando = false;

        setInterval(() => input.focus(), 500);

        input.addEventListener('input', () => {
            let valor = input.value.trim();

            if (valor.length === 10 && !enviando) {
                enviando = true;
                setTimeout(() => form.submit(), 100);
            }
        });

        window.addEventListener('pageshow', () => {
            input.value = '';
            enviando = false;
            input.focus();
        });

        const inputBusca = document.getElementById('buscaNome');
        const resultado = document.getElementById('resultadoBusca');

        inputBusca.addEventListener('input', () => {
            const valor = inputBusca.value;

            if (valor.length < 2) {
                resultado.innerHTML = '';
                return;
            }

            fetch(`../src/actions/buscarColaboradores.php?busca=${valor}`)
                .then(res => res.json())
                .then(data => {
                    resultado.innerHTML = '';

                    data.forEach(c => {
                        resultado.innerHTML += `
                        <div class="p-3 border rounded-lg flex justify-between items-center">
                            <div>
                                <p class="font-semibold">${c.nome}</p>
                                <p class="text-sm text-gray-500">${c.cargo}</p>
                            </div>
                            <button onclick="selecionar(${c.id})"
                                class="bg-blue-600 text-white px-3 py-1 rounded">
                                Selecionar
                            </button>
                        </div>`;
                    });
                });
        });

        function selecionar(id) {
            window.location.href = `../src/actions/buscarColaboradorPorId.php?id=${id}&idTreinamento=<?= $idTreinamento ?>`;
        }
    </script>

</body>

</html>