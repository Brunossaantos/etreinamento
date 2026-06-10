<?php

// include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/treinamento.php');
include(__DIR__ . '/../model/presencaVisitante.php');
// include(__DIR__ . '/../model/presenca.php');

/**
 * DAO responsável pelo gerenciamento completo de Treinamentos.
 * Controla criação, atualização, presença e validação de participantes.
 */
class DaoTreinamento
{
    private $TBL_TREINAMENTO = "treinamento";
    private $TBL_LISTA_PRESENCA = "lista_presenca";
    private $TBL_LISTA_PRESENCA_INVALIDA = "presenca_invalida";
    private $conexao;

    function __construct($conexao)
    {
        // Conexão ativa com o banco de dados
        $this->conexao = $conexao;
    }

    /**
     * Adiciona um novo treinamento no sistema.
     * Regra de negócio: todo treinamento inicia com status ativo (1).
     */
    function adicionarTreinamento(
        $descricaoTreinamento,
        $dataTreinamento,
        $instrutorTreinamento,
        $departamento,
        $conteudoTreinamento,
        $cargaHoraria,
        $local
    ) {
        $statusTreinamento = 1;

        // FIX: armazena resultado de strtoupper em variáveis antes do bind_param
        // bind_param exige referências; funções não podem ser passadas diretamente
        $descricaoUp = strtoupper($descricaoTreinamento);
        $localUp     = strtoupper($local);

        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_TREINAMENTO} 
            (DESCRICAO_TREINAMENTO, DATA_TREINAMENTO, INSTRUTOR, DEPARTAMENTO, CONTEUDO, CARGAHORARIA, STATUS_TREINAMENTO, LOCAL_TREINAMENTO) 
            VALUES (?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "ssiissis",
            $descricaoUp,
            $dataTreinamento,
            $instrutorTreinamento,
            $departamento,
            $conteudoTreinamento,
            $cargaHoraria,
            $statusTreinamento,
            $localUp
        );

        if ($stmt->execute()) {
            // Retorna o ID do treinamento criado (usado para relacionamento com presenças)
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    /**
     * Seleciona um treinamento pelo ID.
     * Retorna objeto Treinamento com todos os dados.
     */
    function selecionarTreinamento($idTreinamento)
    {

        $descricaoTreinamento = null;
        $dataTreinamento = null;
        $horarioTreinamento = null;
        $instrutorTreinamento = null;
        $departamento = null;
        $conteudo = null;
        $statusTreinamento = null;
        $cargaHoraria = null;
        $local = null;

        $stmt = $this->conexao->prepare("
            SELECT * FROM {$this->TBL_TREINAMENTO} 
            WHERE ID_TREINAMENTO = ?
        ");

        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();

        $stmt->bind_result(
            $idTreinamento,
            $descricaoTreinamento,
            $dataTreinamento,
            $instrutorTreinamento,
            $departamento,
            $conteudo,
            $cargaHoraria,
            $statusTreinamento,
            $local
        );
        $stmt->fetch();

        if ($idTreinamento) {
            return new Treinamento(
                $idTreinamento,
                $descricaoTreinamento,
                $dataTreinamento,
                $instrutorTreinamento,
                $departamento,
                $statusTreinamento,
                $conteudo,
                $cargaHoraria,
                $local
            );
        } else {
            return null;
        }
    }

    /**
     * Atualiza dados principais de um treinamento.
     * Inclui descrição, instrutor, departamento, conteúdo e status.
     */
    function atualizarTreinamento(
        $idTreinamento,
        $descricaoTreinamento,
        $dataTreinamento,
        $instrutor,
        $departamento,
        $conteudo,
        $statusTreinamento,
        $cargaHoraria,
        $local
    ) {
        // FIX: armazena resultado de strtoupper em variáveis antes do bind_param
        $descricaoUp = strtoupper($descricaoTreinamento);
        $localUp     = strtoupper($local);

        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_TREINAMENTO} 
            SET DESCRICAO_TREINAMENTO = ?, DATA_TREINAMENTO = ?, INSTRUTOR = ?, DEPARTAMENTO = ?, CONTEUDO = ?, CARGAHORARIA = ?, STATUS_TREINAMENTO = ?, LOCAL_TREINAMENTO = ? 
            WHERE ID_TREINAMENTO = ?
        ");

        $stmt->bind_param(
            "ssiissisi",
            $descricaoUp,
            $dataTreinamento,
            $instrutor,
            $departamento,
            $conteudo,
            $cargaHoraria,
            $statusTreinamento,
            $localUp,
            $idTreinamento
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove um treinamento do sistema.
     * ALERTA: Exclusão física pode causar perda de histórico de presenças.
     */
    function excluirTreinamento($idTreinamento)
    {
        $stmt = $this->conexao->prepare("
            DELETE FROM {$this->TBL_TREINAMENTO} 
            WHERE ID_TREINAMENTO = ?
        ");

        $stmt->bind_param("i", $idTreinamento);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Altera status do treinamento (ativo/inativo).
     * Usado para controle sem remoção do registro.
     */
    function alterarStatusTreinamento($idTreinamento, $statusTreinamento)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_TREINAMENTO} 
            SET STATUS_TREINAMENTO = ? 
            WHERE ID_TREINAMENTO = ?
        ");

        $stmt->bind_param("ii", $statusTreinamento, $idTreinamento);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Registra presença de colaborador em um treinamento.
     * Regra: evita duplicidade de presença para o mesmo colaborador.
     */
    function inserirPresencaTreinamento($idTreinamento, $idColaborador, $horaPresenca)
    {

        $contagem = 0;

        // Verifica se já existe presença registrada
        $consulta = $this->conexao->prepare("
            SELECT COUNT(*) 
            FROM {$this->TBL_LISTA_PRESENCA} 
            WHERE ID_TREINAMENTO = ? AND ID_COLABORADOR = ?
        ");

        $consulta->bind_param("ii", $idTreinamento, $idColaborador);
        $consulta->execute();
        $consulta->bind_result($contagem);
        $consulta->fetch();
        $consulta->close();

        if ($contagem > 0) {
            return false;
        }

        // Insere nova presença válida
        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_LISTA_PRESENCA} 
            VALUES (?,?,?)
        ");

        $stmt->bind_param("iis", $idTreinamento, $idColaborador, $horaPresenca);

        return $stmt->execute();
    }

    /**
     * Registra presença inválida (crachá não identificado no sistema).
     * Evita duplicidade por treinamento + hexadecimal.
     */
    function salvarPresencaInvalida($hexadecimal, $idTreinamento, $horarioDaPresenca)
    {
        $contagem = 0;

        $consulta = $this->conexao->prepare("
            SELECT COUNT(*) 
            FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} 
            WHERE ID_TREINAMENTO = ? AND HEXADECIMAL = ?
        ");

        $consulta->bind_param("is", $idTreinamento, $hexadecimal);
        $consulta->execute();
        $consulta->bind_result($contagem);
        $consulta->fetch();
        $consulta->close();

        if ($contagem > 0) {
            return false;
        }

        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_LISTA_PRESENCA_INVALIDA} 
            VALUES (?,?,?)
        ");

        $stmt->bind_param("iss", $idTreinamento, $hexadecimal, $horarioDaPresenca);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica se um treinamento possui alguma presença registrada.
     */
    function verificarTreinamento($idTreinamento)
    {
        $contagem = 0;

        $consulta = $this->conexao->prepare("
            SELECT COUNT(*) 
            FROM {$this->TBL_LISTA_PRESENCA} 
            WHERE ID_TREINAMENTO = ?
        ");

        $consulta->bind_param("i", $idTreinamento);
        $consulta->execute();
        $consulta->bind_result($contagem);
        $consulta->fetch();
        $consulta->close();

        return $contagem > 0;
    }

    /**
     * Gera lista de presenças de um treinamento.
     */
    function gerarListaPresenca($idTreinamento)
    {
        $listaDePresenca = [];
        $idColaborador = null;
        $horarioPresenca = null;

        $stmt = $this->conexao->prepare("
            SELECT * 
            FROM {$this->TBL_LISTA_PRESENCA} 
            WHERE ID_TREINAMENTO = ? 
            ORDER By HORARIO_PRESENCA ASC
        ");

        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();
        $stmt->bind_result($idTreinamento, $idColaborador, $horarioPresenca);

        while ($stmt->fetch()) {
            $presenca = new Presenca($idTreinamento, $idColaborador, $horarioPresenca);
            $listaDePresenca[] = $presenca;
        }

        $stmt->close();
        return $listaDePresenca;
    }

    /**
     * Gera lista de crachás inválidos registrados no treinamento.
     */
    function gerarListaCrachasInvalidos($idTreinamento)
    {
        $listaCrachaInvalido = [];
        $hexadecimal = null;
        $horaPresenca = null;

        $stmt = $this->conexao->prepare("
            SELECT * 
            FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} 
            WHERE ID_TREINAMENTO = ?
        ");

        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();
        $stmt->bind_result($idTreinamento, $hexadecimal, $horaPresenca);

        while ($stmt->fetch()) {
            $presencaVisitante = new PresencaVisitante($idTreinamento, $hexadecimal, $horaPresenca);
            $listaCrachaInvalido[] = $presencaVisitante;
        }

        $stmt->close();

        // Retorna null caso não existam registros inválidos
        if ($listaCrachaInvalido != null) {
            return $listaCrachaInvalido;
        } else {
            return null;
        }
    }

    /**
     * Pesquisa treinamentos por descrição (LIKE).
     */
    function pesquisarTreinamento($descTreinamento)
    {
        $stmt = $this->conexao->prepare("
            SELECT * 
            FROM {$this->TBL_TREINAMENTO} 
            WHERE DESCRICAO_TREINAMENTO LIKE ?
        ");

        $descTreinamento = "%" . $descTreinamento . "%";
        $stmt->bind_param("s", $descTreinamento);
        $stmt->execute();

        $resultados = $stmt->get_result();
        $treinamentos = array();

        while ($row = $resultados->fetch_assoc()) {
            $treinamento = new Treinamento(
                $row['ID_TREINAMENTO'],
                $row['DESCRICAO_TREINAMENTO'],
                $row['DATA_TREINAMENTO'],
                $row['INSTRUTOR'],
                $row['DEPARTAMENTO'],
                $row['CONTEUDO'],
                $row['CARGAHORARIA'],
                $row['STATUS_TREINAMENTO'],
                $row['LOCAL_TREINAMENTO']
            );

            $treinamentos[] = $treinamento;
        }

        $stmt->close();
        return $treinamentos;
    }

    /**
     * Gera lista completa de treinamentos ordenados por data.
     */
    function gerarListaTreinamentos()
    {
        $treinamentos = [];
        $idTreinamento = null;
        $descricaoTreinamento = null;
        $dataTreinamento = null;
        $horarioTreinamento = null;
        $instrutor = null;
        $departamento = null;
        $conteudo = null;
        $statusTreinamento = null;
        $cargaHoraria = null;
        $local = null;

        $stmt = $this->conexao->prepare("
            SELECT * 
            FROM {$this->TBL_TREINAMENTO} 
            ORDER BY DATA_TREINAMENTO ASC
        ");

        $stmt->execute();
        $stmt->bind_result(
            $idTreinamento,
            $descricaoTreinamento,
            $dataTreinamento,
            $instrutor,
            $departamento,
            $conteudo,
            $cargaHoraria,
            $statusTreinamento,
            $local
        );

        while ($stmt->fetch()) {
            $treinamento = new Treinamento(
                $idTreinamento,
                $descricaoTreinamento,
                $dataTreinamento,
                $instrutor,
                $departamento,
                $conteudo,
                $statusTreinamento,
                $cargaHoraria,
                $local
            );

            $treinamentos[] = $treinamento;
        }

        $stmt->close();

        return $treinamentos;
    }
}