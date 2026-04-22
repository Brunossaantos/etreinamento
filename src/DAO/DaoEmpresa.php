<?php

// include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/empresa.php');

/**
 * DAO responsável pelas operações de banco de dados da entidade Empresa.
 * Centraliza CRUD e consultas relacionadas à tabela empresa.
 */
class DaoEmpresa
{
    private $TBL_EMPRESA = "empresa";
    private $conexao;

    function __construct($conexao)
    {
        // Recebe e mantém a conexão ativa com o banco de dados
        $this->conexao = $conexao;
    }

    /**
     * Adiciona uma nova empresa no sistema.
     * Regra de negócio: toda empresa nova é criada com status ativo (1).
     */
    function adicionarEmpresa($empresa)
    {
        $statusEmpresa = 1;

        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_EMPRESA} (EMPRESA, STATUS_EMPRESA) 
            VALUES (?, ?)
        ");

        // Nome da empresa é padronizado em maiúsculo
        $stmt->bind_param("si", strtoupper($empresa), $statusEmpresa);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adiciona empresa e retorna o ID gerado automaticamente pelo banco.
     * Usado quando é necessário relacionar a empresa com outros registros logo após o cadastro.
     */
    function adicionarEmpresaRetornarId($empresa)
    {
        $statusEmpresa = 1;

        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_EMPRESA} (EMPRESA, STATUS_EMPRESA) 
            VALUES (?, ?)
        ");

        $stmt->bind_param("si", strtoupper($empresa), $statusEmpresa);

        if ($stmt->execute()) {
            // Recupera o último ID inserido na tabela
            $idInserido = $this->conexao->insert_id;
            return $idInserido;
        } else {
            return false;
        }
    }

    /**
     * Busca uma empresa pelo ID.
     * Retorna objeto Empresa com os dados carregados do banco.
     */
    function selecionarEmpresa($idEmpresa)
    {

        $empresa = null;
        $statusEmpresa = null;

        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_EMPRESA} 
            WHERE ID_EMPRESA = ?
        ");

        $stmt->bind_param("i", $idEmpresa);
        $stmt->execute();

        // Mapeia resultado diretamente para variáveis
        $stmt->bind_result($idEmpresa, $empresa, $statusEmpresa);
        $stmt->fetch();

        if ($idEmpresa) {
            return new Empresa($idEmpresa, $empresa, $statusEmpresa);
        } else {
            return null;
        }
    }

    /**
     * Atualiza dados da empresa (nome e status).
     * Nome sempre é armazenado em maiúsculo para padronização.
     */
    function atualizarEmpresa($idEmpresa, $empresa, $statusEmpresa)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_EMPRESA} 
            SET EMPRESA = ?, STATUS_EMPRESA = ? 
            WHERE ID_EMPRESA = ?
        ");

        $stmt->bind_param('sii', strtoupper($empresa), $statusEmpresa, $idEmpresa);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Altera apenas o status da empresa (ativo/inativo).
     * Usado para controle sem excluir registros.
     */
    function alterarStatusEmpresa($idEmpresa, $statusEmpresa)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_EMPRESA} 
            SET STATUS_EMPRESA = ? 
            WHERE ID_EMPRESA = ?
        ");

        $stmt->bind_param("ii", $statusEmpresa, $idEmpresa);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove uma empresa do banco de dados.
     * ALERTA: Exclusão física pode gerar perda de histórico e inconsistência em relacionamentos.
     */
    function excluirEmpresa($idEmpresa)
    {
        $stmt = $this->conexao->prepare("
            DELETE FROM {$this->TBL_EMPRESA} 
            WHERE ID_EMPRESA = ?
        ");

        $stmt->bind_param("i", $idEmpresa);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Pesquisa empresas pelo nome com filtro parcial (LIKE).
     */
    function pesquisarEmpresas($nomeEmpresa)
    {
        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_EMPRESA} 
            WHERE EMPRESA LIKE ?
        ");

        // Permite busca parcial
        $nomeEmpresa = "%" . $nomeEmpresa . "%";
        $stmt->bind_param("s", $nomeEmpresa);
        $stmt->execute();

        $resultados = $stmt->get_result();
        $empresas = array();

        while ($row = $resultados->fetch_assoc()) {
            $empresa = new Empresa(
                $row['ID_EMPRESA'],
                $row['EMPRESA'],
                $row['STATUS_EMPRESA']
            );

            $empresas[] = $empresa;
        }

        $stmt->close();
        return $empresas;
    }

    /**
     * Retorna lista completa de empresas cadastradas.
     */
    function gerarListaEmpresas()
    {

        $empresas = [];
        $idEmpresa = null;
        $nomeEmpresa = null;
        $statusEmpresa = null;

        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_EMPRESA}
        ");

        $stmt->execute();
        $stmt->bind_result($idEmpresa, $nomeEmpresa, $statusEmpresa);

        while ($stmt->fetch()) {
            $empresa = new Empresa($idEmpresa, $nomeEmpresa, $statusEmpresa);
            $empresas[] = $empresa;
        }

        $stmt->close();

        return $empresas;
    }
}