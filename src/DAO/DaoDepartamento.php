<?php

// include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/departamento.php');

/**
 * DAO responsável pelas operações de banco de dados da entidade Departamento.
 * Gerencia CRUD e consultas relacionadas à tabela departamentos.
 */
class DaoDepartamento
{

    private $TBL_DEPARTAMENTO = "departamentos";
    private $conexao;

    function __construct($conexao)
    {
        // Recebe a conexão ativa com o banco de dados
        $this->conexao = $conexao;
    }

    /**
     * Insere um novo departamento no sistema.
     * Regra de negócio: todo novo departamento é criado automaticamente como ativo (STATUS = 1).
     */
    function adicionarDepartamento($nomeDepartamento)
    {
        $statusDoDepartamento = 1;

        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_DEPARTAMENTO} (DEPARTAMENTO, STATUS_DEPARTAMENTO) 
            VALUES (?,?)
        ");

        // Nome é convertido para maiúsculo para padronização dos registros
        $stmt->bind_param("si", strtoupper($nomeDepartamento), $statusDoDepartamento);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Busca um departamento pelo ID.
     * Retorna um objeto Departamento com os dados recuperados do banco.
     */
    function selecionarDepartamento($idDepartamento)
    {
        $nomeDepartamento = null;
        $statusDoDepartamento = null;

        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_DEPARTAMENTO} 
            WHERE ID_DEPARTAMENTO = ?
        ");

        $stmt->bind_param("i", $idDepartamento);
        $stmt->execute();

        // Preenche variáveis diretamente via bind_result
        $stmt->bind_result($idDepartamento, $nomeDepartamento, $statusDoDepartamento);
        $stmt->fetch();

        // Se encontrou registro, cria entidade de domínio
        if ($idDepartamento) {
            return new Departamento($idDepartamento, $nomeDepartamento, $statusDoDepartamento);
        } else {
            return null;
        }
    }

    /**
     * Atualiza nome e status de um departamento existente.
     * Regra: nome sempre armazenado em maiúsculo.
     */
    function atualizarDepartamento($idDepartamento, $nomeDepartamento, $statusDoDepartamento)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_DEPARTAMENTO} 
            SET DEPARTAMENTO = ?, STATUS_DEPARTAMENTO = ? 
            WHERE ID_DEPARTAMENTO = ?
        ");

        $stmt->bind_param("sii", strtoupper($nomeDepartamento), $statusDoDepartamento, $idDepartamento);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove um departamento do banco de dados.
     * ALERTA: Exclusão física pode causar perda irreversível de histórico.
     */
    function excluirDepartamento($idDepartamento)
    {
        $stmt = $this->conexao->prepare("
            DELETE FROM {$this->TBL_DEPARTAMENTO} 
            WHERE ID_DEPARTAMENTO = ?
        ");

        $stmt->bind_param('i', $idDepartamento);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Altera apenas o status do departamento (ativo/inativo).
     * Usado para controle de disponibilidade sem excluir registros.
     */
    function alterarStatusDepartamento($idDepartamento, $statusDoDepartamento)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_DEPARTAMENTO} 
            SET STATUS_DEPARTAMENTO = ? 
            WHERE ID_DEPARTAMENTO = ?
        ");

        $stmt->bind_param("ii", $statusDoDepartamento, $idDepartamento);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retorna todos os departamentos cadastrados no sistema.
     */
    function gerarListaDepartamentos()
    {

        $departamentos = [];
        $idDepartamento = null;
        $nomeDepartamento = null;
        $statusDepartamento = null;

        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_DEPARTAMENTO}
        ");

        $stmt->execute();
        $stmt->bind_result($idDepartamento, $nomeDepartamento, $statusDepartamento);

        while ($stmt->fetch()) {
            $departamento = new Departamento($idDepartamento, $nomeDepartamento, $statusDepartamento);
            $departamentos[] = $departamento;
        }

        $stmt->close();
        return $departamentos;
    }

    /**
     * Pesquisa departamentos pelo nome com busca parcial (LIKE).
     */
    function pesquisarDepartamentos($pesquisa)
    {
        $sql = "SELECT * FROM {$this->TBL_DEPARTAMENTO} WHERE DEPARTAMENTO LIKE ?";
        $stmt = $this->conexao->prepare($sql);

        $pesquisa = "%" . $pesquisa . "%"; // Permite busca parcial
        $stmt->bind_param("s", $pesquisa);
        $stmt->execute();

        $resultados = $stmt->get_result();
        $departamentos = array();

        while ($row = $resultados->fetch_assoc()) {
            $departamento = new Departamento(
                $row['ID_DEPARTAMENTO'],
                $row['DEPARTAMENTO'],
                $row['STATUS_DEPARTAMENTO']
            );

            $departamentos[] = $departamento;
        }

        $stmt->close();

        return $departamentos;
    }

    /**
     * Conta quantos colaboradores estão vinculados a um departamento.
     * Usado para validações antes de exclusão ou alterações estruturais.
     */
    function contarColab($idDepartamento)
    {
        $contagem = 0;

        try {

            $sql = "SELECT COUNT(*) FROM colaboradores WHERE DEPARTAMENTO = ?";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bind_param("i", $idDepartamento);
            $stmt->execute();
            $stmt->bind_result($contagem);

            $stmt->fetch();
            $stmt->close();

            return $contagem;
        } catch (Exception $e) {
            // ALERTA: Exibir erro diretamente pode expor informações sensíveis em produção
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
}