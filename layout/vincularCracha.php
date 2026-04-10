<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit();
}

$idTreinamento = $_GET['idTreinamento'] ?? null;
$cracha = $_GET['hexadecimal'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vincular Crachá</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen p-4">

<div class="max-w-4xl mx-auto bg-white p-4 md:p-6 rounded-2xl shadow">

<h1 class="text-xl md:text-2xl font-bold text-blue-600 mb-4 text-center">
Vincular Crachá: <?= htmlspecialchars($cracha) ?>
</h1>

<!-- 🔍 BUSCA -->
<input type="text" id="busca"
placeholder="Digite nome ou matrícula..."
class="w-full px-4 py-3 border rounded-xl mb-4 text-lg">

<!-- 📋 LISTA -->
<div id="lista" class="space-y-2"></div>

<!-- 🔙 VOLTAR -->
<div class="mt-4 text-center">
<a href="listaDePresenca.php?idTreinamento=<?= $idTreinamento ?>"
class="text-blue-600 underline">
← Voltar
</a>
</div>

</div>

<script>

const input = document.getElementById('busca');
const lista = document.getElementById('lista');

function buscar(valor = '') {
    fetch(`../src/actions/buscarColaboradores.php?busca=${valor}`)
    .then(res => res.json())
    .then(data => {
        lista.innerHTML = '';

        if (data.length === 0) {
            lista.innerHTML = '<p class="text-center text-gray-500">Nenhum encontrado</p>';
            return;
        }

        data.forEach(c => {
            lista.innerHTML += `
                <div class="flex flex-col md:flex-row md:items-center justify-between p-3 border rounded-xl shadow-sm">

                    <div>
                        <p class="font-semibold">${c.nome}</p>
                        <p class="text-sm text-gray-500">Matrícula: ${c.matricula}</p>
                        <p class="text-sm text-gray-500">${c.cargo}</p>
                    </div>

                    <button onclick="vincular(${c.id})"
                        class="mt-2 md:mt-0 bg-green-600 text-white px-4 py-2 rounded-lg">
                        Vincular
                    </button>
                </div>
            `;
        });
    });
}

// ⚡ Vincular sem reload
function vincular(idColaborador) {

    fetch(`../src/actions/vincularPresenca.php?idTreinamento=<?= $idTreinamento ?>&idColaborador=${idColaborador}&hexadecimal=<?= $cracha ?>`)
    .then(res => res.text())
    .then(() => {
        window.location.href = `listaDePresenca.php?idTreinamento=<?= $idTreinamento ?>&sucesso=cracha_vinculado`;
    })
    .catch(() => {
        alert("Erro ao vincular crachá");
    });
}

// 🚀 Carrega inicial
buscar();

</script>

</body>
</html>