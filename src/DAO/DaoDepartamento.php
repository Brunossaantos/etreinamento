<?php   

//include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/departamento.php');

class DaoDepartamento{

    private $TBL_DEPARTAMENTO = "departamentos";
    private $conexao;

    function __construct($conexao){
        $this->conexao = $conexao;
    }

    function adicionarDepartamento($nomeDepartamento){
        $statusDoDepartamento = 1;
        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_DEPARTAMENTO} (DEPARTAMENTO, STATUS_DEPARTAMENTO) VALUES (?,?)");
        $stmt->bind_param("si", strtoupper($nomeDepartamento), $statusDoDepartamento);
        
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function selecionarDepartamento($idDepartamento){
        $nomeDepartamento = null;
        $statusDoDepartamento = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_DEPARTAMENTO} WHERE ID_DEPARTAMENTO = ?");
        $stmt->bind_param("i", $idDepartamento);
        $stmt->execute();
        $stmt->bind_result($idDepartamento, $nomeDepartamento, $statusDoDepartamento);
        $stmt->fetch();

        if($idDepartamento){
            return new Departamento($idDepartamento, $nomeDepartamento, $statusDoDepartamento);
        } else {
            return null;
        }
    }

    function atualizarDepartamento($idDepartamento, $nomeDepartamento, $statusDoDepartamento){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_DEPARTAMENTO} SET DEPARTAMENTO = ?, STATUS_DEPARTAMENTO = ? WHERE ID_DEPARTAMENTO = ?");
        $stmt->bind_param("sii", strtoupper($nomeDepartamento), $statusDoDepartamento, $idDepartamento);
        
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }
    
    function excluirDepartamento($idDepartamento){
        $stmt = $this->conexao->prepare("DELETE FROM {$this->TBL_DEPARTAMENTO} WHERE ID_DEPARTAMENTO = ?");
        $stmt->bind_param('i', $idDepartamento);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function alterarStatusDepartamento($idDepartamento, $statusDoDepartamento){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_DEPARTAMENTO} SET STATUS_DEPARTAMENTO = ? WHERE ID_DEPARTAMENTO = ?");
        $stmt->bind_param("ii", $statusDoDepartamento, $idDepartamento);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function gerarListaDepartamentos(){
        
        $departamentos = [];
        $idDepartamento = null;
        $nomeDepartamento = null;
        $statusDepartamento = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_DEPARTAMENTO}");
        $stmt->execute();
        $stmt->bind_result($idDepartamento, $nomeDepartamento, $statusDepartamento);

        while($stmt->fetch()){
            $departamento = new Departamento($idDepartamento, $nomeDepartamento, $statusDepartamento);
            $departamentos[] = $departamento;
        }

        $stmt->close();
        return $departamentos;
    }

    function pesquisarDepartamentos($pesquisa) {
        $sql = "SELECT * FROM {$this->TBL_DEPARTAMENTO} WHERE DEPARTAMENTO LIKE ?";
        $stmt = $this->conexao->prepare($sql);
        $pesquisa = "%" . $pesquisa . "%"; // Adicione curingas para pesquisa parcial
        $stmt->bind_param("s", $pesquisa);
        $stmt->execute();
    
        $resultados = $stmt->get_result();
        $departamentos = array();
    
        while ($row = $resultados->fetch_assoc()) {
            $departamento = new Departamento($row['ID_DEPARTAMENTO'], $row['DEPARTAMENTO'], $row['STATUS_DEPARTAMENTO']);
            // $departamento->setIdDepartamento($row['ID_DEPARTAMENTO']);
            // $departamento->setNomeDepartamento($row['DEPARTAMENTO']);
            // $departamento->setStatusDepartamento($row['STATUS_DEPARTAMENTO']);
            $departamentos[] = $departamento;
        }
    
        $stmt->close();
    
        return $departamentos;
    }

    function contarColab($idDepartamento) {
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
            // Lidar com exceções, se necessário
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
    
    


}


?>