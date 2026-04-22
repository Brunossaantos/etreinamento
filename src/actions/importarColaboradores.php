<?php

// Carrega variáveis de ambiente
require_once __DIR__ . '/../src/config/env.php';

// Conexão com banco gestor
require_once __DIR__ . '/../src/database/conexao2.php';

// Instancia conexão
$conexao = new ConexaoGestor();
$conn = $conexao->conectar();

/**
 * Controla permissão de importação
 * Retorna caminho do CSV ou null
 */
function permitirImport($permissao)
{
    if ($permissao) {

        echo "<h1>Relatório da importação</h1>";

        // ALERTA: Caminho fixo local (ideal mover para .env)
        return "C:/Users/danilo.franco/Desktop/ListaCrachá/colabUdlog.csv";
    } else {

        echo '<h1>Você não tem permissão para importar!</h1>';
        return null;
    }
}

// Define caminho do CSV
$caminhoCsv = permitirImport(true);

// Valida existência do arquivo
if (!$caminhoCsv || !file_exists($caminhoCsv)) {
    die("Arquivo CSV não encontrado");
}

// Abre arquivo CSV
if (($handle = fopen($caminhoCsv, "r")) !== false) {

    // Prepared statements (segurança contra SQL Injection)
    $stmtSelect = $conn->prepare("SELECT ID_COLABORADORES FROM tb_colaboradores WHERE MATRICULA = ?");
    $stmtUpdate = $conn->prepare("
        UPDATE tb_colaboradores 
        SET NOME = ?, FILIAL = ?, FUNCAO = ?
        WHERE MATRICULA = ?
    ");
    $stmtInsert = $conn->prepare("
        INSERT INTO tb_colaboradores 
        (NOME, FILIAL, FUNCAO, MATRICULA, ATIVO)
        VALUES (?, ?, ?, ?, 1)
    ");

    // Percorre linhas do CSV
    while (($data = fgetcsv($handle, 1000, ";")) !== false) {

        // Dados do CSV
        $matricula = trim($data[0]);
        $nome = trim($data[1]);
        $empresa = trim($data[2]);
        $cargo = trim($data[3]);

        // Verifica se já existe
        $stmtSelect->bind_param("s", $matricula);
        $stmtSelect->execute();
        $result = $stmtSelect->get_result();

        if ($result->num_rows > 0) {

            // Atualiza registro existente
            $stmtUpdate->bind_param("ssss", $nome, $empresa, $cargo, $matricula);

            if ($stmtUpdate->execute()) {
                echo "Atualizado: $matricula<br>";
            } else {
                echo "Erro ao atualizar: " . $stmtUpdate->error . "<br>";
            }
        } else {

            // Insere novo registro
            $stmtInsert->bind_param("ssss", $nome, $empresa, $cargo, $matricula);

            if ($stmtInsert->execute()) {
                echo "Inserido: $matricula<br>";
            } else {
                echo "Erro ao inserir: " . $stmtInsert->error . "<br>";
            }
        }
    }

    // Fecha arquivo CSV
    fclose($handle);

    // Fecha statements
    $stmtSelect->close();
    $stmtUpdate->close();
    $stmtInsert->close();
} else {

    echo "Erro ao abrir o CSV";
}

// Fecha conexão
$conn->close();