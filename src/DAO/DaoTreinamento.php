<?php   

//include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/treinamento.php');
include(__DIR__ . '/../model/presencaVisitante.php');
//include(__DIR__ . '/../model/presenca.php');

class DaoTreinamento {
    private $TBL_TREINAMENTO = "treinamento";
    private $TBL_LISTA_PRESENCA = "lista_presenca";
    private $TBL_LISTA_PRESENCA_INVALIDA = "presenca_invalida";
    private $conexao;

    function __construct($conexao){
        $this->conexao = $conexao;
    }

    function adicionarTreinamento($descricaoTreinamento, $dataTreinamento, $instrutorTreinamento, $departamento , $conteudoTreinamento, $cargaHoraria, $local){
        $statusTreinamento = 1;
        
        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_TREINAMENTO} (DESCRICAO_TREINAMENTO, DATA_TREINAMENTO, INSTRUTOR, DEPARTAMENTO, CONTEUDO, CARGAHORARIA, STATUS_TREINAMENTO, LOCAL_TREINAMENTO) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssiissis", strtoupper($descricaoTreinamento), $dataTreinamento, $instrutorTreinamento, $departamento, $conteudoTreinamento, $cargaHoraria ,$statusTreinamento, strtoupper($local));
    
        if($stmt->execute()){
            // Retorna o ID da linha inserida
            return $stmt->insert_id;
        } else {
            return false;
        }
    }    

    function selecionarTreinamento($idTreinamento){

        $descricaoTreinamento = null;
        $dataTreinamento = null;
        $horarioTreinamento = null;
        $instrutorTreinamento = null;
        $departamento = null;
        $conteudo = null;
        $statusTreinamento = null;
        $cargaHoraria = null;
        $local = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_TREINAMENTO} WHERE ID_TREINAMENTO = ?");
        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();
        $stmt->bind_result($idTreinamento, $descricaoTreinamento, $dataTreinamento, $instrutorTreinamento, $departamento, $conteudo, $cargaHoraria, $statusTreinamento, $local);
        $stmt->fetch();

        if($idTreinamento){
            return new Treinamento ($idTreinamento, $descricaoTreinamento, $dataTreinamento, $instrutorTreinamento, $departamento, $statusTreinamento, $conteudo, $cargaHoraria, $local);
        } else {
            return null;
        }

    }

    function atualizarTreinamento ($idTreinamento, $descricaoTreinamento, $dataTreinamento, $instrutor, $departamento, $conteudo, $statusTreinamento, $cargaHoraria, $local){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_TREINAMENTO} SET DESCRICAO_TREINAMENTO = ?, DATA_TREINAMENTO = ?, INSTRUTOR = ?, DEPARTAMENTO = ?, CONTEUDO = ?, CARGAHORARIA = ?, STATUS_TREINAMENTO = ?, LOCAL_TREINAMENTO = ? WHERE ID_TREINAMENTO = ?");
        $stmt->bind_param("ssiissisi", strtoupper($descricaoTreinamento), $dataTreinamento, $instrutor, $departamento, $conteudo, $cargaHoraria, $statusTreinamento, $local, $idTreinamento);
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function excluirTreinamento($idTreinamento){
        $stmt = $this->conexao->prepare("DELETE FROM {$this->TBL_TREINAMENTO} WHERE ID_TREINAMENTO = ?");
        $stmt->bind_param("i", $idTreinamento);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }

    }

    function alterarStatusTreinamento ($idTreinamento, $statusTreinamento){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_TREINAMENTO} SET STATUS_TREINAMENTO = ? WHERE ID_TREINAMENTO = ?");
        $stmt->bind_param("ii", $statusTreinamento, $idTreinamento);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function inserirPresencaTreinamento($idTreinamento, $idColaborador, $horaPresenca){        
        $contagem = 0;
        $consulta = $this->conexao->prepare("SELECT COUNT(*) FROM {$this->TBL_LISTA_PRESENCA} WHERE ID_TREINAMENTO = ? AND ID_COLABORADOR = ?");
        $consulta->bind_param("ii", $idTreinamento, $idColaborador);
        $consulta->execute();
        $consulta->bind_result($contagem);
        $consulta->fetch();
        $consulta->close();    
        
        if ($contagem > 0) {
            return false;
        }    
        
        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_LISTA_PRESENCA} VALUES (?,?,?)");
        $stmt->bind_param("iis", $idTreinamento, $idColaborador, $horaPresenca);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    function salvarPresencaInvalida($hexadecimal, $idTreinamento, $horarioDaPresenca){
        $contagem = 0;
        $consulta = $this->conexao->prepare("SELECT COUNT(*) FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} WHERE ID_TREINAMENTO = ? AND HEXADECIMAL = ?");
        $consulta->bind_param("is", $idTreinamento, $hexadecimal);
        $consulta->execute();
        $consulta->bind_result($contagem);
        $consulta->fetch();
        $consulta->close();

        if($contagem > 0){
            return false;
        }

        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_LISTA_PRESENCA_INVALIDA} VALUES (?,?,?)");
        $stmt->bind_param("iss", $idTreinamento, $hexadecimal, $horarioDaPresenca);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function verificarTreinamento($idTreinamento){
        $contagem = 0;
        $consulta = $this->conexao->prepare("SELECT COUNT(*) FROM {$this->TBL_LISTA_PRESENCA} WHERE ID_TREINAMENTO = ?");
        $consulta->bind_param("i", $idTreinamento);
        $consulta->execute();
        $consulta->bind_result($contagem);
        $consulta->fetch();
        $consulta->close();

        if($contagem > 0){
            return true;
        } else {
            return false;
        }
    }

    function gerarListaPresenca($idTreinamento){
        $listaDePresenca = [];
        $idColaborador = null;        
        $horarioPresenca = null;
        
        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_LISTA_PRESENCA} WHERE ID_TREINAMENTO = ? ORDER By HORARIO_PRESENCA ASC");
        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();
        $stmt->bind_result($idTreinamento, $idColaborador, $horarioPresenca);

        while($stmt->fetch()){
            $presenca = new Presenca($idTreinamento, $idColaborador, $horarioPresenca);
            $listaDePresenca[] = $presenca;
        }

        $stmt->close();
        return $listaDePresenca;
    }

    function gerarListaCrachasInvalidos($idTreinamento){
        $listaCrachaInvalido = [];
        $hexadecimal = null;
        $horaPresenca = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} WHERE ID_TREINAMENTO = ?");
        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();
        $stmt->bind_result($idTreinamento, $hexadecimal, $horaPresenca);

        while($stmt->fetch()){
            $presencaVisitante = new PresencaVisitante($idTreinamento, $hexadecimal, $horaPresenca);
            $listaCrachaInvalido[] = $presencaVisitante;
        }

        $stmt->close();

        if($listaCrachaInvalido != null){
            return $listaCrachaInvalido;
        } else {
            return null;
        }        
    }

    function pesquisarTreinamento($descTreinamento){
        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_TREINAMENTO} WHERE DESCRICAO_TREINAMENTO LIKE ?");
        $descTreinamento = "%".$descTreinamento."%";
        $stmt->bind_param("s", $descTreinamento);
        $stmt->execute();

        $resultados = $stmt->get_result();
        $treinamentos = array();

        while($row = $resultados->fetch_assoc()){
            $treinamento = new Treinamento($row['ID_TREINAMENTO'], $row['DESCRICAO_TREINAMENTO'], $row['DATA_TREINAMENTO'], $row['INSTRUTOR'], $row['DEPARTAMENTO'], $row['CONTEUDO'], $row['CARGAHORARIA'], $row['STATUS_TREINAMENTO'], $row['LOCAL_TREINAMENTO']);
            $treinamentos[] = $treinamento;
        }

        $stmt->close();
        return $treinamentos;

    }

    function gerarListaTreinamentos(){
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

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_TREINAMENTO} ORDER BY DATA_TREINAMENTO ASC");
        $stmt->execute();
        $stmt->bind_result($idTreinamento, $descricaoTreinamento, $dataTreinamento, $instrutor, $departamento, $conteudo, $cargaHoraria, $statusTreinamento, $local);

        while($stmt->fetch()){
            $treinamento = new Treinamento($idTreinamento, $descricaoTreinamento, $dataTreinamento, $instrutor, $departamento, $conteudo, $statusTreinamento, $cargaHoraria, $local);
            $treinamentos[] = $treinamento;
        }

        $stmt->close();

        return $treinamentos;
    }
    
}




?>