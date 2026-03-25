<?php
// Configurações do banco de dados
$hostname = "localhost";
$username = "root";
$password = "UdlogT3c@";
$database = "etreinamento";

// Caminho para o arquivo CSV
$arquivoCSV = "C:\Users\danilo.franco\Desktop\ListaCrachá\updatecrachas.csv";

// Conexão com o banco de dados
$mysqli = new mysqli($hostname, $username, $password, $database);

// Verifica se a conexão com o banco de dados ocorreu com sucesso
if ($mysqli->connect_errno) {
    echo "Falha na conexão com o MySQL: " . $mysqli->connect_error;
    exit;
}

// Abre o arquivo CSV para leitura
if (($handle = fopen($arquivoCSV, "r")) !== FALSE) {
    // Loop através das linhas do arquivo CSV
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        // Obtenha os valores da matrícula e do hexadecimal do CSV
        $matricula = $data[0];
        $hexadecimal = $data[1];

        // Atualize o registro no banco de dados com base na matrícula
        $sql = "UPDATE colaboradores SET HEXADECIMAL = ? WHERE MATRICULA = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ss", $hexadecimal, $matricula);

        if ($stmt->execute()) {
            echo "Registro atualizado com sucesso para matrícula: " . $matricula . "<br>";
        } else {
            echo "Erro ao atualizar registro para matrícula: " . $matricula . "<br>";
        }

        $stmt->close();
    }
    fclose($handle);
}

// Feche a conexão com o banco de dados
$mysqli->close();
?>
