<?php

include(__DIR__ . '/../model/presenca.php');

/**
 * DAO responsável pelo controle de presença em treinamentos.
 * Gerencia registros de presença válidos e inválidos (crachás não reconhecidos).
 */
class DaoPresenca
{
    private $TBL_LISTAPRESENCA = "lista_presenca";
    private $TBL_LISTA_PRESENCA_INVALIDA = "presenca_invalida";
    private $conexao;

    function __construct($conexao)
    {
        // Conexão ativa com o banco de dados
        $this->conexao = $conexao;
    }

    /**
     * Insere a presença de um colaborador em um treinamento.
     * Regra: o horário de presença é obrigatório para registro válido.
     */
    function inserirPresenca($idTreinamento, $idColaborador, $horarioPresenca, $origemColaborador = 'gestor')
    {
        $stmt = $this->conexao->prepare("
        INSERT INTO {$this->TBL_LISTAPRESENCA} 
        (ID_TREINAMENTO, ID_COLABORADOR, HORARIO_PRESENCA, ORIGEM_COLABORADOR)
        VALUES (?, ?, ?, ?)
    ");

        $stmt->bind_param("iiss", $idTreinamento, $idColaborador, $horarioPresenca, $origemColaborador);

        return $stmt->execute();
    }

    /**
     * Remove registros de presença inválida (visitantes ou crachás não autorizados).
     * Usado para limpeza de dados após validação do treinamento.
     */
    function excluirPresencaVisitante($idTreinamento, $hexadecimal)
    {
        $stmt = $this->conexao->prepare("
            DELETE FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} 
            WHERE HEXADECIMAL = ? AND ID_TREINAMENTO = ?
        ");

        $stmt->bind_param("si", $hexadecimal, $idTreinamento);
        return $stmt->execute();
    }

    /**
     * Gera lista de presenças válidas de um treinamento específico.
     * Retorna objetos Presenca com dados de participantes confirmados.
     */
    function gerarListaPresenca($idTreinamento)
    {
        $presencas = [];

        $stmt = $this->conexao->prepare("
        SELECT 
            ID_TREINAMENTO, 
            ID_COLABORADOR, 
            HORARIO_PRESENCA,
            ORIGEM_COLABORADOR
        FROM {$this->TBL_LISTAPRESENCA}
        WHERE ID_TREINAMENTO = ?
        AND HORARIO_PRESENCA IS NOT NULL
        GROUP BY ID_TREINAMENTO, ID_COLABORADOR, ORIGEM_COLABORADOR
        ORDER BY HORARIO_PRESENCA ASC
    ");

        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();

        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $presencas[] = new Presenca(
                $row['ID_TREINAMENTO'],
                $row['ID_COLABORADOR'],
                $row['HORARIO_PRESENCA'],
                $row['ORIGEM_COLABORADOR'] ?? 'etreinamento'
            );
        }

        return $presencas;
    }

    /**
     * Conta o total de presenças registradas em um treinamento.
     * Usado para relatórios e controle de participação.
     */
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

    /**
     * Conta quantos crachás inválidos foram registrados no treinamento.
     * Usado para auditoria de acessos não autorizados.
     */
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

    /**
     * Verifica se um colaborador já possui presença registrada no treinamento.
     * Evita duplicidade de registros de presença.
     */
    function verificarPresencaExistente($idTreinamento, $idColaborador, $origemColaborador = 'gestor')
    {
        $stmt = $this->conexao->prepare("
        SELECT 1 
        FROM {$this->TBL_LISTAPRESENCA}
        WHERE ID_TREINAMENTO = ? 
        AND ID_COLABORADOR = ?
        AND ORIGEM_COLABORADOR = ?
        LIMIT 1
    ");

        $stmt->bind_param("iis", $idTreinamento, $idColaborador, $origemColaborador);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
}