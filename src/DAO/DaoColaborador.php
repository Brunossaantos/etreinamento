<?php

include(__DIR__ . '/../model/colaborador.php');

/**
 * DAO responsável por todas as operações de banco de dados relacionadas à entidade Colaborador.
 * Centraliza consultas, atualizações e buscas na tabela tb_colaboradores.
 */
class DaoColaborador
{
    private $conexao;
    private $TBL = "tb_colaboradores"; // Tabela principal de colaboradores

    function __construct($conexao)
    {
        // Recebe a conexão com o banco de dados (mysqli)
        $this->conexao = $conexao;
    }

    /**
     * Prepara uma query SQL utilizando a conexão ativa.
     * Garante que a query seja válida antes da execução.
     */
    private function preparar($sql)
    {
        $stmt = $this->conexao->prepare($sql);

        if (!$stmt) {
            // ALERTA: Em produção, o uso de die interrompe toda execução do sistema
            // e pode expor detalhes sensíveis do banco de dados.
            die("Erro SQL: " . $this->conexao->error);
        }

        return $stmt;
    }

    /**
     * Atualiza dados principais do colaborador.
     * Regra de negócio: apenas informações básicas são atualizadas (nome, cargo, crachá e matrícula).
     */
    function atualizarColaborador($id, $nome, $empresa, $cargo, $cracha, $matricula, $departamento)
    {
        $stmt = $this->preparar("
        UPDATE {$this->TBL}
        SET 
            NOME = ?,
            FUNCAO = ?,
            TAG_CARTAO = ?,
            MATRICULA = ?
        WHERE ID_COLABORADORES = ?
    ");

        // Bind dos parâmetros garantindo segurança contra SQL Injection
        $stmt->bind_param(
            "ssssi",
            $nome,
            $cargo,
            $cracha,
            $matricula,
            $id
        );

        return $stmt->execute();
    }

    /**
     * Busca um colaborador pelo ID.
     * Retorna um objeto Colaborador com todos os dados principais formatados.
     */
    function selecionarColaborador($idColaborador)
    {
        $stmt = $this->preparar("
            SELECT 
                ID_COLABORADORES AS ID_COLABORADOR,
                NOME,
                FILIAL AS EMPRESA,
                FUNCAO AS CARGO,
                TAG_CARTAO AS HEXADECIMAL,
                MATRICULA,
                DEPTO AS DEPARTAMENTO,
                ATIVO AS STATUS_COLABORADOR
            FROM {$this->TBL}
            WHERE ID_COLABORADORES = ?
        ");

        $stmt->bind_param('i', $idColaborador);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Converte o resultado em um objeto de domínio Colaborador
        if ($row) {
            return new Colaborador(
                $row['ID_COLABORADOR'],
                $row['NOME'],
                $row['EMPRESA'],
                $row['CARGO'],
                $row['HEXADECIMAL'],
                $row['MATRICULA'],
                $row['DEPARTAMENTO'],
                $row['STATUS_COLABORADOR']
            );
        }

        return null;
    }

    /**
     * Retorna o ID do colaborador com base no código hexadecimal do crachá.
     * Utilizado em validações de acesso por cartão.
     */
    function retornarIdpeloHexa($hexadecimal)
    {
        $stmt = $this->preparar("
            SELECT ID_COLABORADORES AS ID_COLABORADOR
            FROM {$this->TBL}
            WHERE TAG_CARTAO = ?
        ");

        $stmt->bind_param("s", $hexadecimal);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? $row['ID_COLABORADOR'] : -1;
    }

    /**
     * Busca dados básicos do colaborador via crachá.
     * Usado principalmente em validações rápidas de identificação.
     */
    function buscarPorCrachaDetalhado($hexadecimal)
    {
        $stmt = $this->preparar("
            SELECT 
                ID_COLABORADORES AS ID_COLABORADOR,
                NOME
            FROM {$this->TBL}
            WHERE TAG_CARTAO = ?
        ");

        $stmt->bind_param("s", $hexadecimal);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? [
            "id" => $row['ID_COLABORADOR'],
            "nome" => $row['NOME']
        ] : null;
    }

    /**
     * Atualiza o crachá (TAG_CARTAO) de um colaborador.
     * Usado no processo de troca ou reemissão de cartões.
     */
    function atualizarCracha($idColaborador, $hexadecimal)
    {
        $stmt = $this->preparar("
            UPDATE {$this->TBL}
            SET TAG_CARTAO = ?
            WHERE ID_COLABORADORES = ?
        ");

        $stmt->bind_param("si", $hexadecimal, $idColaborador);
        return $stmt->execute();
    }

    /**
     * Pesquisa colaboradores pelo nome (busca parcial).
     * Retorna lista de colaboradores filtrados.
     */
    function pesquisarColaborador($pesquisaColab)
    {
        // ALERTA: Erro de SQL (SSELECT) pode quebrar a execução da query
        $stmt = $this->preparar("
            SSELECT 
                ID_COLABORADORES AS ID_COLABORADOR,
                NOME,
                FILIAL AS EMPRESA,
                FUNCAO AS CARGO,
                TAG_CARTAO AS HEXADECIMAL,
                MATRICULA,
                DEPTO AS DEPARTAMENTO,
                ATIVO AS STATUS_COLABORADOR
            FROM {$this->TBL}
            WHERE NOME LIKE ?
            ORDER BY ATIVO DESC
        ");

        $pesquisaColab = "%" . $pesquisaColab . "%";
        $stmt->bind_param("s", $pesquisaColab);
        $stmt->execute();

        $result = $stmt->get_result();
        $colaboradores = [];

        while ($row = $result->fetch_assoc()) {
            $colaboradores[] = new Colaborador(
                $row['ID_COLABORADOR'],
                $row['NOME'],
                $row['EMPRESA'],
                $row['CARGO'],
                $row['HEXADECIMAL'],
                $row['MATRICULA'],
                $row['DEPARTAMENTO'],
                $row['STATUS_COLABORADOR']
            );
        }

        return $colaboradores;
    }

    /**
     * Lista colaboradores com ou sem filtro de busca.
     * Permite busca por nome ou matrícula.
     */
    function listarColaboradores($busca = "")
    {
        if (!empty($busca)) {
            $sql = "
                SELECT 
                    ID_COLABORADORES AS ID_COLABORADOR,
                    NOME,
                    FILIAL AS EMPRESA,
                    FUNCAO AS CARGO,
                    TAG_CARTAO AS HEXADECIMAL,
                    MATRICULA,
                    DEPTO AS DEPARTAMENTO,
                    ATIVO AS STATUS_COLABORADOR
                FROM {$this->TBL}
                WHERE NOME LIKE ? OR MATRICULA LIKE ?
                ORDER BY NOME ASC
            ";

            $buscaLike = "%" . $busca . "%";

            $stmt = $this->preparar($sql);
            $stmt->bind_param("ss", $buscaLike, $buscaLike);
        } else {
            $stmt = $this->preparar("
                SELECT 
                    ID_COLABORADORES AS ID_COLABORADOR,
                    NOME,
                    FILIAL AS EMPRESA,
                    FUNCAO AS CARGO,
                    TAG_CARTAO AS HEXADECIMAL,
                    MATRICULA,
                    DEPTO AS DEPARTAMENTO,
                    ATIVO AS STATUS_COLABORADOR
                FROM {$this->TBL}
                ORDER BY NOME ASC
            ");
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $colaboradores = [];

        while ($row = $result->fetch_assoc()) {
            $colaboradores[] = new Colaborador(
                $row['ID_COLABORADOR'],
                $row['NOME'],
                $row['EMPRESA'],
                $row['CARGO'],
                $row['HEXADECIMAL'],
                $row['MATRICULA'],
                $row['DEPARTAMENTO'],
                $row['STATUS_COLABORADOR']
            );
        }

        return $colaboradores;
    }

    /**
     * Retorna o status atual (ativo/inativo) do colaborador.
     * Usado para validações de acesso e permissões.
     */
    function recuperarStatusAtualColaborador($idColaborador)
    {
        $stmt = $this->preparar("
            SELECT ATIVO AS STATUS_COLABORADOR
            FROM {$this->TBL}
            WHERE ID_COLABORADORES = ?
        ");

        $stmt->bind_param("i", $idColaborador);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? $row['STATUS_COLABORADOR'] : null;
    }

    // ❌ BLOQUEADOS (gestor controla sistema)
    function adicionarColaborador()
    {
        throw new Exception("Cadastro deve ser feito no gestor.");
    }

    function excluirColaborador()
    {
        throw new Exception("Exclusão deve ser feita no gestor.");
    }

    function alterarStatusColaborador()
    {
        throw new Exception("Status deve ser alterado no gestor.");
    }

    /**
     * Retorna lista completa de colaboradores sem filtros.
     */
    function gerarListaColaboradores()
    {
        return $this->listarColaboradores();
    }
}