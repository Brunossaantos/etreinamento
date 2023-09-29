<?php  

// include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/empresa.php');

class DaoEmpresa {
    private $TBL_EMPRESA = "empresa";
    private $conexao;

    function __construct($conexao){
        $this->conexao = $conexao;
    }

    function adicionarEmpresa($empresa){
        $statusEmpresa = 1;
        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_EMPRESA} (EMPRESA, STATUS_EMPRESA) VALUES (?, ?)");
        $stmt->bind_param("si", strtoupper($empresa), $statusEmpresa);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function adicionarEmpresaRetornarId($empresa){
        $statusEmpresa = 1;
        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_EMPRESA} (EMPRESA, STATUS_EMPRESA) VALUES (?, ?)");
        $stmt->bind_param("si", strtoupper($empresa), $statusEmpresa);
    
        if($stmt->execute()){            
            $idInserido = $this->conexao->insert_id;
            return $idInserido;
        } else {
            return false;
        }
    }    

    function selecionarEmpresa($idEmpresa){

        $empresa = null;
        $statusEmpresa = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_EMPRESA} WHERE ID_EMPRESA = ?");
        $stmt->bind_param("i", $idEmpresa);
        $stmt->execute();
        $stmt->bind_result($idEmpresa, $empresa, $statusEmpresa);
        $stmt->fetch();

        if($idEmpresa){
            return new Empresa ($idEmpresa, $empresa, $statusEmpresa);
        } else {
            return null;
        }
    }

    function atualizarEmpresa($idEmpresa, $empresa, $statusEmpresa){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_EMPRESA} SET EMPRESA = ?, STATUS_EMPRESA = ? WHERE ID_EMPRESA = ?");
        $stmt->bind_param('sii', strtoupper($empresa), $statusEmpresa, $idEmpresa);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function alterarStatusEmpresa($idEmpresa, $statusEmpresa){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_EMPRESA} SET STATUS_EMPRESA = ? WHERE ID_EMPRESA = ?");
        $stmt->bind_param("ii", $statusEmpresa, $idEmpresa);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function excluirEmpresa($idEmpresa){
        $stmt = $this->conexao->prepare("DELETE FROM {$this->TBL_EMPRESA} WHERE ID_EMPRESA = ?");
        $stmt->bind_param("i", $idEmpresa);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function pesquisarEmpresas($nomeEmpresa){
        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_EMPRESA} WHERE EMPRESA LIKE ?");
        $nomeEmpresa = "%".$nomeEmpresa."%";
        $stmt->bind_param("s", $nomeEmpresa);
        $stmt->execute();
        
        $resultados = $stmt->get_result();
        $empresas = array();

        while($row = $resultados->fetch_assoc()){
            $empresa = new Empresa ($row['ID_EMPRESA'], $row['EMPRESA'], $row['STATUS_EMPRESA']);
            $empresas[] = $empresa;
        }

        $stmt->close();
        return $empresas;
    }

    function gerarListaEmpresas(){

        $empresas = [];
        $idEmpresa = null;
        $nomeEmpresa = null;
        $statusEmpresa = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_EMPRESA}");
        $stmt->execute();
        $stmt->bind_result($idEmpresa, $nomeEmpresa, $statusEmpresa);

        while($stmt->fetch()){
            $empresa = new Empresa($idEmpresa, $nomeEmpresa, $statusEmpresa);
            $empresas[] = $empresa;
        }

        $stmt->close();

        return $empresas;
    }

}

?>