<?php

class PresencaVisitante{
    private $hexadecimal;
    private $idTreinamento;
    private $horarioDaPresenca;

    function __construct($idTreinamento, $hexadecimal, $horarioDaPresenca){
        $this->setIdTreinamento($idTreinamento);
        $this->setHexadecimal($hexadecimal);
        $this->setHorarioDaPresenca($horarioDaPresenca);
    }

    function setIdTreinamento($idTreinamento){
        $this->idTreinamento = $idTreinamento;
    }

    function setHexadecimal($hexadecimal){
        $this->hexadecimal = $hexadecimal;
    }

    function setHorarioDaPresenca($horarioDaPresenca){
        $this->horarioDaPresenca = $horarioDaPresenca;
    }

    function getIdTreinamento(){
        return $this->idTreinamento;
    }

    function getHexadecimal(){
        return $this->hexadecimal;
    }

    function getHorarioDaPresenca(){
        return $this->horarioDaPresenca;
    }

    function __toString(){
        return "ID treinamento: ".$this->getIdTreinamento()
        ."<br>Hexadecimal: ".$this->getHexadecimal()
        ."<br>Horario da presença: ".$this->getHorarioDaPresenca()."<br>";
    }
}

?>