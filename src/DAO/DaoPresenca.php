<?php

include(__DIR__ . '/../model/presenca.php');

class DaoPresenca
{
    private $TBL_LISTAPRESENCA = "lista_presenca";
    private $TBL_LISTA_PRESENCA_INVALIDA = "presenca_invalida";
    private $conexao;

    function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    function inserirPresenca($idTreinamento, $idColaborador, $horarioPresenca)
    {
        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_LISTAPRESENCA} 
            (ID_TREINAMENTO, ID_COLABORADOR, HORARIO_PRESENCA)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("iis", $idTreinamento, $idColaborador, $horarioPresenca);

        return $stmt->execute();
    }

    function excluirPresencaVisitante($idTreinamento, $hexadecimal)
    {
        $stmt = $this->conexao->prepare("
            DELETE FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} 
            WHERE HEXADECIMAL = ? AND ID_TREINAMENTO = ?
        ");

        $stmt->bind_param("si", $hexadecimal, $idTreinamento);
        return $stmt->execute();
    }

    function gerarListaPresenca($idTreinamento)
    {
        $presencas = [];

        $stmt = $this->conexao->prepare("
            SELECT ID_TREINAMENTO, ID_COLABORADOR, HORARIO_PRESENCA 
            FROM {$this->TBL_LISTAPRESENCA} 
            WHERE ID_TREINAMENTO = ? 
            AND HORARIO_PRESENCA IS NOT NULL
        ");

        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $presencas[] = new Presenca(
                $row['ID_TREINAMENTO'],
                $row['ID_COLABORADOR'],
                $row['HORARIO_PRESENCA']
            );
        }

        return $presencas;
    }

    function contarPresenca($idTreinamento)
    {
        $stmt = $this->conexao->prepare("
            SELECT COUNT(*) as total 
            FROM {$this->TBL_LISTAPRESENCA} 
            WHERE ID_TREINAMENTO = ?
        ");

        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return $result['total'] ?? 0;
    }

    function contarCrachasInvalidos($idTreinamento)
    {
        $stmt = $this->conexao->prepare("
            SELECT COUNT(*) as total 
            FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} 
            WHERE ID_TREINAMENTO = ?
        ");

        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return $result['total'] ?? 0;
    }

    function verificarPresencaExistente($idTreinamento, $idColaborador)
    {
        $stmt = $this->conexao->prepare("
            SELECT 1 
            FROM {$this->TBL_LISTAPRESENCA}
            WHERE ID_TREINAMENTO = ? 
            AND ID_COLABORADOR = ?
        ");

        $stmt->bind_param("ii", $idTreinamento, $idColaborador);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
}
