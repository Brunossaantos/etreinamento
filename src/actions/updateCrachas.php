<?php

/*require_once __DIR__ . '/../config/env.php';

// 📁 Caminho do CSV
$arquivoCSV = "C:\Users\danilo.franco\Desktop\ListaCrachá\updatecrachas.csv";

// 🔌 Conexão (GESTOR)
$mysqli = new mysqli(
    $_ENV['DB2_HOST'],
    $_ENV['DB2_USER'],
    $_ENV['DB2_PASS'],
    $_ENV['DB2_NAME']
);

// ❌ Erro de conexão
if ($mysqli->connect_errno) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

// 🔎 Verifica arquivo
if (!file_exists($arquivoCSV)) {
    die("Arquivo CSV não encontrado.");
}

// 📂 Abre CSV
if (($handle = fopen($arquivoCSV, "r")) !== false) {

    echo "<h2>Atualização de Crachás</h2>";

    // 🔁 Loop
    while (($data = fgetcsv($handle, 1000, ";")) !== false) {

        $matricula = trim($data[0] ?? '');
        $hexadecimal = trim($data[1] ?? '');

        // ⚠️ validação básica
        if (empty($matricula) || empty($hexadecimal)) {
            echo "Linha ignorada (dados inválidos)<br>";
            continue;
        }

        // 🔧 SQL CORRIGIDO (PADRÃO GESTOR)
        $stmt = $mysqli->prepare("
            UPDATE tb_colaboradores
            SET TAG_CARTAO = ?
            WHERE MATRICULA = ?
        ");

        if (!$stmt) {
            echo "Erro prepare: " . $mysqli->error . "<br>";
            continue;
        }

        $stmt->bind_param("ss", $hexadecimal, $matricula);

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {
                echo "✅ Atualizado: $matricula<br>";
            } else {
                echo "⚠️ Não encontrado: $matricula<br>";
            }
        } else {
            echo "❌ Erro ao atualizar: $matricula<br>";
        }

        $stmt->close();
    }

    fclose($handle);
} else {
    echo "Erro ao abrir o CSV.";
}

// 🔚 Fecha conexão
$mysqli->close();
*/