<?php

//include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/presenca.php');

class DaoPresenca {
    private $TBL_LISTAPRESENCA = "lista_presenca";
    private $conexao;
    function __construct($conexao){
        $this->conexao=$conexao;
    }
    function adicionarColaboradorListaPresenca($idTreinamento, $idColaborador){

        $horaPresenca = null;

        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_LISTAPRESENCA} (ID_TREINAMENTO, ID_COLABORADOR, HORARIO_PRESENCA) VALUES ($idTreinamento, $idColaborador, $horaPresenca)");
        $stmt->bind_param("iis", $idTreinamento, $idColaborador, $horaPresenca);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }
    function inserirPresenca($idTreinamento, $idColaborador, $horaPresenca){
        
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_LISTAPRESENCA} SET HORARIO_PRESENCA = ? WHERE ID_TREINAMENTO = ? AND ID_COLABORADOR = ?");
        $stmt->bind_param("sii", $horaPresenca, $idTreinamento, $idColaborador);

        if($stmt->execute()){            
            return true;
        } else {
            return false;
        }
    }

    function gerarListaPresenca($idTreinamento){
        $presencas = [];
        $idColaborador = null;
        $horaPresenca = null;
    
        $stmt = $this->conexao->prepare("SELECT ID_TREINAMENTO, ID_COLABORADOR, HORARIO_PRESENCA FROM {$this->TBL_LISTAPRESENCA} WHERE ID_TREINAMENTO = ? AND HORARIO_PRESENCA IS NOT NULL");
        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();
        $stmt->bind_result($idTreinamento, $idColaborador, $horaPresenca);    
        
        while ($stmt->fetch()) {
            $presenca = new Presenca($idTreinamento, $idColaborador, $horaPresenca);
            $presencas[] = $presenca;
        }
    
        $stmt->close();
    
        return $presencas;
    }
    
    function contarPresenca($idTreinamento){
        $contador = 0;
    
        $stmt = $this->conexao->prepare("SELECT COUNT(ID_TREINAMENTO) FROM lista_presenca WHERE ID_TREINAMENTO = ?");
        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();
        $stmt->bind_result($contador);
        $stmt->fetch();
        $stmt->close();
    
        return $contador;
    }
    
    
}

?>