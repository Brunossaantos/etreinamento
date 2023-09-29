<?php 

//include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/colaborador.php');

class DaoColaborador{

    private $TBL_COLABORADOR = "colaboradores";
    private $conexao;

    function __construct($conexao){
        $this->conexao = $conexao;
    }
    
    function adicionarColaborador($nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento){
       $statusColaborador = 1;
       $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_COLABORADOR} (NOME, EMPRESA, CARGO, HEXADECIMAL, MATRICULA, DEPARTAMENTO, STATUS_COLABORADOR) VALUES (?,?,?,?,?,?,?)");
       $stmt->bind_param("sisssii", strtoupper($nome), $empresa, strtoupper($cargo), $hexadecimal, $matricula, $departamento, $statusColaborador);

       if($stmt->execute()){
            return true;
       } else {
            return false;
       }
    }

    function selecionarColaborador($idColaborador){
        $nome = null;
        $empresa = null;
        $cargo = null;
        $hexadecimal = null;
        $matricula = null;
        $departamento = null;
        $statusColaborador = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_COLABORADOR} WHERE ID_COLABORADOR = ?");
        $stmt->bind_param('i', $idColaborador);
        $stmt->execute();
        $stmt->bind_result($idColaborador, $nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento, $statusColaborador);
        $stmt->fetch();
    
        if ($idColaborador) {
            return new Colaborador($idColaborador, $nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento, $statusColaborador);
        } else {
            return null; // Colaborador não encontrado
        }
    }

    function pesquisarColaboradorPeloNome($nomeColaborador){
        
        $idColaborador = null;
        $nome = null;
        $empresa = null;
        $cargo = null;
        $hexadecimal = null;
        $matricula = null;
        $departamento = null;
        $statusColaborador = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_COLABORADOR} WHERE NOME = %?%");
        $stmt->bind_param("s", $nomeColaborador);
        $stmt->execute();
        $stmt->bind_result($idColaborador, $nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento, $statusColaborador);
        $stmt->fetch();

        if($idColaborador != null){
            return new Colaborador($idColaborador, $nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento, $statusColaborador);
        } else {
            return null;
        }
    }

    function excluirColaborador($idColaborador){
        $stmt = $this->conexao->prepare("DELETE FROM {$this->TBL_COLABORADOR} WHERE ID_COLABORADOR = ?");
        $stmt->bind_param("i", $idColaborador);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function retornarIdpeloHexa($hexadecimal){
        
        $idColaborador = null;
        
        $stmt = $this->conexao->prepare("SELECT ID_COLABORADOR FROM {$this->TBL_COLABORADOR} WHERE HEXADECIMAL = ?");
        $stmt->bind_param("s", $hexadecimal);
        $stmt->execute();
        $stmt->bind_result($idColaborador);
        $stmt->fetch();

        if($idColaborador != null){
            return $idColaborador;
        } else {
            return -1;
        }
    }

    function recuperarStatusAtualColaborador($idColaborador){
        $statusColaborador = null;
        $stmt = $this->conexao->prepare("SELECT STATUS_COLABORADOR FROM {$this->TBL_COLABORADOR} WHERE ID_COLABORADOR = ?");
        $stmt->bind_param("i", $idColaborador);
        $stmt->execute();
        $stmt->bind_result($statusColaborador);
        $stmt->fetch();

        return $statusColaborador;
    }

    function atualizarColaborador($idColaborador, $nome, $empresa, $cargo, $hexadecimal, $matricula, $departamento){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_COLABORADOR} set NOME = ?, EMPRESA = ?, CARGO = ?, HEXADECIMAL = ?, MATRICULA = ?, DEPARTAMENTO = ? WHERE ID_COLABORADOR = ?");
        $stmt->bind_param("sisssii", strtoupper($nome), $empresa, strtoupper($cargo), $hexadecimal, $matricula, $departamento, $idColaborador);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function alterarStatusColaborador($idColaborador, $statusColaborador){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_COLABORADOR} set STATUS_COLABORADOR = ? WHERE ID_COLABORADOR = ?");
        $stmt->bind_param("ii", $statusColaborador, $idColaborador);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function pesquisarColaborador($pesquisaColab){
        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_COLABORADOR} WHERE NOME LIKE ? order by STATUS_COLABORADOR desc");
        $pesquisaColab = "%" . $pesquisaColab . "%";
        $stmt->bind_param("s", $pesquisaColab);
        $stmt->execute();

        $resultados = $stmt->get_result();
        $colaboradores = array();

        while($row = $resultados->fetch_assoc()){
            $colaborador = new Colaborador($row['ID_COLABORADOR'], $row['NOME'], $row['EMPRESA'], $row['CARGO'], $row['HEXADECIMAL'], $row['MATRICULA'], $row['DEPARTAMENTO'], $row['STATUS_COLABORADOR']);
            $colaboradores[] = $colaborador;
        }

        $stmt->close();
        return $colaboradores;
    }

    function gerarListaColaboradores(){

        $colaboradores = [];
        $idColaborador = null;
        $nomeColaborador = null;
        $empresa = null;
        $cargo = null;
        $hexadecimal = null;
        $matricula = null;
        $departamento = null;
        $statusColabodorador = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_COLABORADOR} order by NOME ASC");
        $stmt->execute();
        $stmt->bind_result($idColaborador, $nomeColaborador, $empresa, $cargo, $hexadecimal, $matricula, $departamento, $statusColabodorador);

        while($stmt->fetch()){
            $colaborador = new Colaborador($idColaborador, $nomeColaborador, $empresa, $cargo, $hexadecimal, $matricula, $departamento, $statusColabodorador);
            $colaboradores[] = $colaborador;
        }

        $stmt->close();
        return $colaboradores;

    }

}

?>