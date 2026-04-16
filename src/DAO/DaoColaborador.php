<?php

include(__DIR__ . '/../model/colaborador.php');

class DaoColaborador
{
    private $conexao;
    private $TBL = "tb_colaboradores"; // ✅ corrigido

    function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    private function preparar($sql)
    {
        $stmt = $this->conexao->prepare($sql);

        if (!$stmt) {
            die("Erro SQL: " . $this->conexao->error);
        }

        return $stmt;
    }


    function selecionarColaborador($idColaborador)
    {
        $stmt = $this->preparar("
            SELECT 
    ID_COLABORADORES AS ID_COLABORADOR,
    NOME,
    FILIAL AS EMPRESA,        -- 🔥 correto
    FUNCAO AS CARGO,
    TAG_CARTAO AS HEXADECIMAL,
    MATRICULA,
    DEPTO AS DEPARTAMENTO,    -- 🔥 correto
    ATIVO AS STATUS_COLABORADOR
FROM {$this->TBL}
            WHERE ID_COLABORADORES = ?
        ");

        $stmt->bind_param('i', $idColaborador);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

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

    function pesquisarColaborador($pesquisaColab)
    {
        $stmt = $this->preparar("
            SSELECT 
    ID_COLABORADORES AS ID_COLABORADOR,
    NOME,
    FILIAL AS EMPRESA,        -- 🔥 correto
    FUNCAO AS CARGO,
    TAG_CARTAO AS HEXADECIMAL,
    MATRICULA,
    DEPTO AS DEPARTAMENTO,    -- 🔥 correto
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

    function listarColaboradores($busca = "")
    {
        if (!empty($busca)) {
            $sql = "
                SELECT 
    ID_COLABORADORES AS ID_COLABORADOR,
    NOME,
    FILIAL AS EMPRESA,        -- 🔥 correto
    FUNCAO AS CARGO,
    TAG_CARTAO AS HEXADECIMAL,
    MATRICULA,
    DEPTO AS DEPARTAMENTO,    -- 🔥 correto
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
    FILIAL AS EMPRESA,        -- 🔥 correto
    FUNCAO AS CARGO,
    TAG_CARTAO AS HEXADECIMAL,
    MATRICULA,
    DEPTO AS DEPARTAMENTO,    -- 🔥 correto
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

    // ❌ BLOQUEADOS (gestor controla)
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

    function gerarListaColaboradores()
    {
        return $this->listarColaboradores();
    }
}
