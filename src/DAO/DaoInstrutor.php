<?php

// include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/instrutor.php');

/**
 * DAO responsável pelas operações de banco de dados da entidade Instrutor.
 * Centraliza CRUD e consultas da tabela instrutores.
 */
class DaoInstrutor
{
    private $TBL_INSTRUTOR = "instrutores";
    private $conexao;

    function __construct($conexao)
    {
        // Recebe a conexão ativa com o banco de dados
        $this->conexao = $conexao;
    }

    /**
     * Adiciona um novo instrutor no sistema.
     * Regra de negócio: todo instrutor nasce com status ativo (1).
     */
    function adicionarInstrutor($nome, $departamento)
    {
        $statusInstrutor = 1;

        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_INSTRUTOR} (NOME, DEPARTAMENTO, STATUS_INSTRUTOR) 
            VALUES (?,?,?)
        ");

        // Nome padronizado em maiúsculo para consistência de dados
        $stmt->bind_param("sii", strtoupper($nome), $departamento, $statusInstrutor);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Busca um instrutor pelo ID.
     * Retorna objeto Instrutor com dados carregados do banco.
     */
    function selecionarInstrutor($idInstrutor)
    {

        $nome = null;
        $departamento = null;
        $statusInstrutor = null;

        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_INSTRUTOR} 
            WHERE ID_INSTRUTORES = ?
        ");

        $stmt->bind_param('i', $idInstrutor);
        $stmt->execute();

        // Mapeamento direto do resultado para variáveis
        $stmt->bind_result($idInstrutor, $nome, $departamento, $statusInstrutor);
        $stmt->fetch();

        if ($idInstrutor) {
            return new Instrutor($idInstrutor, $nome, $departamento, $statusInstrutor);
        } else {
            return null;
        }
    }

    /**
     * Atualiza dados de um instrutor existente.
     * Inclui nome, departamento e status.
     */
    function atualizarInstrutor($idInstrutor, $nomeInstrutor, $departamentoInstrutor, $statusInstrutor)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_INSTRUTOR} 
            SET NOME = ?, DEPARTAMENTO = ?, STATUS_INSTRUTOR = ? 
            WHERE ID_INSTRUTORES = ?
        ");

        $stmt->bind_param("siii", strtoupper($nomeInstrutor), $departamentoInstrutor, $statusInstrutor, $idInstrutor);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Altera apenas o status do instrutor (ativo/inativo).
     * Usado para controle sem remoção do registro.
     */
    function alterarStatusInstrutor($idInstrutor, $statusInstrutor)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_INSTRUTOR} 
            SET STATUS_INSTRUTOR = ? 
            WHERE ID_INSTRUTORES = ?
        ");

        $stmt->bind_param("ii", $statusInstrutor, $idInstrutor);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove um instrutor do banco de dados.
     * ALERTA: Exclusão física pode causar perda de histórico e inconsistência no sistema.
     */
    function excluirInstrutor($idInstrutor)
    {
        $stmt = $this->conexao->prepare("
            DELETE FROM {$this->TBL_INSTRUTOR} 
            WHERE ID_INSTRUTORES = ?
        ");

        $stmt->bind_param('i', $idInstrutor);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Pesquisa instrutores por nome utilizando LIKE (busca parcial).
     */
    function pesquisarInstrutores($nomeInstrutor)
    {
        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_INSTRUTOR} 
            WHERE NOME LIKE ?
        ");

        $nomeInstrutor = "%" . $nomeInstrutor . "%";
        $stmt->bind_param("s", $nomeInstrutor);
        $stmt->execute();

        $resultados = $stmt->get_result();
        $instrutores = array();

        while ($row = $resultados->fetch_assoc()) {
            $instrutor = new Instrutor(
                $row['ID_INSTRUTORES'],
                $row['NOME'],
                $row['DEPARTAMENTO'],
                $row['STATUS_INSTRUTOR']
            );

            $instrutores[] = $instrutor;
        }

        $stmt->close();
        return $instrutores;
    }

    /**
     * Retorna lista completa de instrutores cadastrados.
     */
    function gerarListaInstrurores()
    {
        $instrutores = [];

        $idInstrutor = null;
        $nomeInstrutor = null;
        $departamento = null;
        $statusInstrutor = null;

        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_INSTRUTOR}
        ");

        $stmt->execute();
        $stmt->bind_result($idInstrutor, $nomeInstrutor, $departamento, $statusInstrutor);

        while ($stmt->fetch()) {
            $instrutor = new Instrutor($idInstrutor, $nomeInstrutor, $departamento, $statusInstrutor);
            $instrutores[] = $instrutor;
        }

        $stmt->close();
        return $instrutores;
    }
}

// Código de teste comentado (mantido apenas para referência de desenvolvimento)
// $conexao = new Conexao();
// $daoInstrutor = new DaoInstrutor($conexao->conectar());

// $instrutor = $daoInstrutor->selecionarInstrutor(8);
// echo $instrutor;