<?php

include(__DIR__ . '/../model/colaborador.php');

class DaoColaborador
{
    private $TBL_COLABORADOR = "colaboradores";
    private $conexao;

    function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    function adicionarColaborador($nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento)
    {
        $statusColaborador = 1;

        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_COLABORADOR}
            (NOME, EMPRESA, CARGO, HEXADECIMAL, MATRICULA, DEPARTAMENTO, STATUS_COLABORADOR)
            VALUES (?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "sisssii",
            strtoupper($nome),
            $empresa,
            strtoupper($cargo),
            $hexadecimal,
            $matricula,
            $departamento,
            $statusColaborador
        );

        return $stmt->execute();
    }

    function selecionarColaborador($idColaborador)
    {
        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_COLABORADOR}
            WHERE ID_COLABORADOR = ?
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
        $stmt = $this->conexao->prepare("
        SELECT ID_COLABORADOR 
        FROM {$this->TBL_COLABORADOR}
        WHERE HEXADECIMAL = ?
    ");

        $stmt->bind_param("s", $hexadecimal);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            return $row['ID_COLABORADOR'];
        }

        return -1;
    }

    // 🔥 NOVO — usado para detectar duplicidade + mostrar nome
    function buscarPorCrachaDetalhado($hexadecimal)
    {
        $stmt = $this->conexao->prepare("
        SELECT ID_COLABORADOR, NOME 
        FROM {$this->TBL_COLABORADOR}
        WHERE HEXADECIMAL = ?
    ");

        $stmt->bind_param("s", $hexadecimal);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            return [
                "id" => $row['ID_COLABORADOR'],
                "nome" => $row['NOME']
            ];
        }

        return null;
    }
    function atualizarCracha($idColaborador, $hexadecimal)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_COLABORADOR}
            SET HEXADECIMAL = ?
            WHERE ID_COLABORADOR = ?
        ");

        $stmt->bind_param("si", $hexadecimal, $idColaborador);

        return $stmt->execute();
    }

    function pesquisarColaborador($pesquisaColab)
    {
        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_COLABORADOR}
            WHERE NOME LIKE ?
            ORDER BY STATUS_COLABORADOR DESC
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
        $colaboradores = [];

        if (!empty($busca)) {
            $sql = "
                SELECT * FROM {$this->TBL_COLABORADOR}
                WHERE NOME LIKE ? OR MATRICULA LIKE ?
                ORDER BY NOME ASC
            ";

            $buscaLike = "%" . $busca . "%";

            $stmt = $this->conexao->prepare($sql);
            $stmt->bind_param("ss", $buscaLike, $buscaLike);
        } else {
            $stmt = $this->conexao->prepare("
                SELECT * FROM {$this->TBL_COLABORADOR}
                ORDER BY NOME ASC
            ");
        }

        $stmt->execute();
        $result = $stmt->get_result();

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

    function excluirColaborador($idColaborador)
    {
        $stmt = $this->conexao->prepare("
            DELETE FROM {$this->TBL_COLABORADOR}
            WHERE ID_COLABORADOR = ?
        ");

        $stmt->bind_param("i", $idColaborador);
        return $stmt->execute();
    }

    function alterarStatusColaborador($idColaborador, $statusColaborador)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_COLABORADOR}
            SET STATUS_COLABORADOR = ?
            WHERE ID_COLABORADOR = ?
        ");

        $stmt->bind_param("ii", $statusColaborador, $idColaborador);
        return $stmt->execute();
    }
    function gerarListaColaboradores()
    {
        return $this->listarColaboradores();
    }

    function recuperarStatusAtualColaborador($idColaborador)
    {
        $stmt = $this->conexao->prepare("
        SELECT STATUS_COLABORADOR 
        FROM {$this->TBL_COLABORADOR}
        WHERE ID_COLABORADOR = ?
    ");

        $stmt->bind_param("i", $idColaborador);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            return $row['STATUS_COLABORADOR'];
        }

        return null;
    }
}
