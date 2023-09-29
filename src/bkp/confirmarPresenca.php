<?php
include "conexao.php";

// Verifique se o parâmetro 'hexadecimal' existe na URL
if (isset($_GET['hexadecimal'])) {
    $hexadecimal = $_GET['hexadecimal'];

    $sql = "SELECT colaboradores.*, departamentos.DEPARTAMENTO 
            FROM colaboradores
            JOIN departamentos ON colaboradores.DEPARTAMENTO = departamentos.id_departamento 
            WHERE hexadecimal = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $hexadecimal);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Exiba os dados encontrados no formulário
        $row = $resultado->fetch_assoc();
        $idColaborador = $row['ID_COLABORADOR'];
        $nome = strtoupper($row["NOME"]);
        $matricula = $row["MATRICULA"];
        $departamento = strtoupper($row["DEPARTAMENTO"]);
        $cargo = strtoupper($row["CARGO"]);
        $horaAtaual = date('d-m-Y H:i:s');
        
        // Inserir o registro na tabela 'lista_presenca' com a data e hora atuais
        $sql_inserir = "INSERT INTO lista_presenca (ID_TREINAMENTO, ID_COLABORADOR, HORARIO_PRESENCA) VALUES (1, ?, ?)";
        $stmt_inserir = $conn->prepare($sql_inserir);
        $stmt_inserir->bind_param("is", $idColaborador, $horaAtaual);
        $stmt_inserir->execute();

        if ($stmt_inserir->affected_rows > 0) {
            echo "Registro de presença inserido com sucesso.";
        } else {
            echo "Erro ao inserir o registro de presença.";
        }
        $stmt_inserir->close();

        // Redirecione de volta para o formulário com os dados preenchidos
        header("Location: ../listaDeTreinamento.php?nome=$nome&matricula=$matricula&departamento=$departamento&cargo=$cargo&hexadecimal=$hexadecimal");
        exit();

    } else {
        header("Location: ../listaDeTreinamento.php?nome=$nome&matricula=$matricula&departamento=$departamento&cargo=$cargo&hexadecimal=$hexadecimal");        
        echo "Nenhum registro encontrado para o hexadecimal: " . $hexadecimal;
    }

    // Feche a declaração preparada e a conexão
    $stmt->close();
    $conn->close();

} else {
    echo "O parâmetro 'hexadecimal' não foi fornecido na URL.";
}
?>