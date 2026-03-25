<?php

//include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/presenca.php');

class DaoPresenca {
    private $TBL_LISTAPRESENCA = "lista_presenca";
    private $TBL_LISTA_PRESENCA_INVALIDA = "presenca_invalida";
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

    function inserirPresenca($idTreinamento, $idColaborador, $horarioPresenca){

        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_LISTAPRESENCA} VALUES (?,?,?)");
        $stmt->bind_param("iis", $idTreinamento, $idColaborador, $horarioPresenca);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function excluirPresencaVisitante($idTreinamento, $hexadecimal){

        $stmt = $this->conexao->prepare("DELETE FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} WHERE HEXADECIMAL = ? AND ID_TREINAMENTO = ?");
        $stmt->bind_param("si", $hexadecimal, $idTreinamento);
        
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

    function contarCrachasInvalidos($idTreinamento){
        $contador = 0;
        $stmt = $this->conexao->prepare("SELECT COUNT(ID_TREINAMENTO) FROM {$this->TBL_LISTA_PRESENCA_INVALIDA} WHERE ID_TREINAMENTO = ?");
        $stmt->bind_param("i", $idTreinamento);
        $stmt->execute();
        $stmt->bind_result($contador);
        $stmt->fetch();
        $stmt->close();

        return $contador;        
    }    
    
}

?>