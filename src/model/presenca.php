<?php

class Presenca{

    private $idTreinamento;
    private $idColaborador;
    private $horaPresenca;

    function __construct($idTreinamento, $idColaborador, $horaPresenca){
        $this->setIdTreinamento($idTreinamento);
        $this->setIdColaborador($idColaborador);
        $this->setHoraPresenca($horaPresenca);
    }

    function setIdTreinamento($idTreinamento){
        $this->idTreinamento = $idTreinamento;
    }

    function setIdColaborador($idColaborador){
        $this->idColaborador = $idColaborador;
    }

    function setHoraPresenca($horaPresenca){
        $this->horaPresenca = $horaPresenca;
    }

    function getIdTreinamento(){
        return $this->idTreinamento;
    }

    function getIdColaborador(){
        return $this->idColaborador;
    }

    function getHoraPresenca(){
        return $this->horaPresenca;
    }

    function dataAtual(){
        return $this->horaAtual = date('d-M-Y H:i:s');
    }

    /**
     * ID do treinamento:
     * ID do colaborador:
     * Horario da presença: 
    */

    function __toString(){
        return "<br>ID do treinamento: ".$this->getIdTreinamento()
        ."<br> ID do colaborador: ".$this->getIdColaborador()
        ."<br> Horario da presença: ".$this->getHoraPresenca()."<br>";
    }

}

?>