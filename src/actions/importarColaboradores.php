<?php
// Defina suas credenciais de banco de dados
$servername = "localhost";
$username = "root";
$password = "UdlogT3c@";
$dbname = "etreinamento";

// Crie uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifique se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}


function permitirImport($permissão){
    if($permissão == true){
        // Caminho do arquivo CSV
        echo "<h1>Relatório da importação</h1>";
        $csvFile = "C:\Users\danilo.franco\Desktop\ListaCrachá\colabUdlog.csv";
        return $csvFile;
    } else {
        $csvFile = null;
        echo '<h1>Você não tem permissão para importar!</h1>';
        return $csvFile;
    }
}


// Abra o arquivo CSV para leitura
if (($handle = fopen(permitirImport(false), "r")) !== FALSE) {
    // Loop através das linhas do arquivo CSV
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        // Extrair os dados do arquivo CSV
        $matricula = $data[0];
        $nome = $data[1];
        $empresa = $data[2];
        $cargo = $data[3];
        
        // Verifique se a matrícula já existe no banco de dados
        $sql = "SELECT * FROM colaboradores WHERE matricula = '$matricula'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            // Matrícula já existe no banco de dados, faça um UPDATE
            $sql = "UPDATE colaboradores SET nome = '$nome', empresa = '$empresa', cargo = '$cargo' WHERE matricula = '$matricula'";
            if ($conn->query($sql) === TRUE) {
                echo "Registro atualizado com sucesso para matrícula: $matricula<br>";
            } else {
                echo "Erro ao atualizar registro: " . $conn->error;
            }
        } else {
            // Matrícula não existe no banco de dados, faça um INSERT
            $sql = "INSERT INTO colaboradores (nome, empresa, cargo, matricula, status_colaborador) VALUES ('$nome', '$empresa', '$cargo', '$matricula', 1)";
            if ($conn->query($sql) === TRUE) {
                echo "Novo registro inserido com sucesso para matrícula: $matricula<br>";
            } else {
                echo "Erro ao inserir novo registro: " . $conn->error;
            }
        }
    }
    
    // Feche o arquivo CSV
    fclose($handle);
} else {
    echo "Erro ao abrir o arquivo CSV";
}

// Feche a conexão com o banco de dados
$conn->close();
?>
