<?php   

// include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/instrutor.php');

class DaoInstrutor {
    private $TBL_INSTRUTOR = "instrutores";
    private $conexao;

    function __construct($conexao){
        $this->conexao = $conexao;
    }
    
    function adicionarInstrutor($nome, $departamento){
        $statusInstrutor = 1;
        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_INSTRUTOR} (NOME, DEPARTAMENTO, STATUS_INSTRUTOR) VALUES (?,?,?)");
        $stmt->bind_param("sii", strtoupper($nome), $departamento, $statusInstrutor);
        
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function selecionarInstrutor($idInstrutor){
        
        $nome = null;
        $departamento = null;
        $statusInstrutor = null;
        
        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_INSTRUTOR} WHERE ID_INSTRUTORES = ?");
        $stmt->bind_param('i', $idInstrutor);
        $stmt->execute();
        $stmt->bind_result($idInstrutor, $nome, $departamento, $statusInstrutor);
        $stmt->fetch();

        if($idInstrutor){
            return new Instrutor($idInstrutor, $nome, $departamento, $statusInstrutor);
        } else {
            return null;
        }        
    }

    function atualizarInstrutor($idInstrutor, $nomeInstrutor, $departamentoInstrutor, $statusInstrutor){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_INSTRUTOR} SET NOME = ?, DEPARTAMENTO = ?, STATUS_INSTRUTOR = ? WHERE ID_INSTRUTORES = ?");
        $stmt->bind_param("siii", strtoupper($nomeInstrutor), $departamentoInstrutor, $statusInstrutor, $idInstrutor);
        
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function alterarStatusInstrutor($idInstrutor, $statusInstrutor){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_INSTRUTOR} SET STATUS_INSTRUTOR = ? WHERE ID_INSTRUTORES = ?");
        $stmt->bind_param("ii", $statusInstrutor, $idInstrutor);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function excluirInstrutor($idInstrutor){
        $stmt = $this->conexao->prepare("DELETE FROM {$this->TBL_INSTRUTOR} WHERE ID_INSTRUTORES = ?");
        $stmt->bind_param('i',$idInstrutor);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function pesquisarInstrutores($nomeInstrutor){
        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_INSTRUTOR} WHERE NOME LIKE ?");
        $nomeInstrutor = "%".$nomeInstrutor."%";
        $stmt->bind_param("s", $nomeInstrutor);
        $stmt->execute();

        $resultados = $stmt->get_result();
        $instrutores = array();

        while($row = $resultados->fetch_assoc()){
            $instrutor = new Instrutor($row['ID_INSTRUTORES'], $row['NOME'], $row['DEPARTAMENTO'], $row['STATUS_INSTRUTOR']);
            $instrutores[] = $instrutor;
        }

        $stmt->close();
        return $instrutores;
    }

    function gerarListaInstrurores(){
        $instrutores = [];

        $idInstrutor = null;
        $nomeInstrutor = null;
        $departamento = null;
        $statusInstrutor = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_INSTRUTOR}");
        $stmt->execute();
        $stmt->bind_result($idInstrutor, $nomeInstrutor, $departamento, $statusInstrutor);

        while($stmt->fetch()){
            $instrutor = new Instrutor($idInstrutor, $nomeInstrutor, $departamento, $statusInstrutor);
            $instrutores[] = $instrutor;
        }
        
        $stmt->close();
        return $instrutores;
    }

}

// $conexao = new Conexao();
// $daoInstrutor = new DaoInstrutor($conexao->conectar());

// $instrutor = $daoInstrutor->selecionarInstrutor(8);
// echo $instrutor;

?>