<?php 

class Instrutor{

    private $idInstrutor;
    private $nomeInstrutor;
    private $departamentoInstrutor;
    private $statusInstrutor;

    function __construct($idInstrutor, $nomeInstrutor, $departamentoInstrutor, $statusInstrutor){
        $this->setIdIsntrutor($idInstrutor);
        $this->setNomeInstrutor($nomeInstrutor);
        $this->setDepartamentoInstrutor($departamentoInstrutor);
        $this->setStatusInstrutor($statusInstrutor);
    }

    function setIdIsntrutor($idInstrutor){
        $this->idInstrutor=$idInstrutor;
    }

    function setNomeInstrutor($nomeInstrutor){
        $this->nomeInstrutor= $nomeInstrutor;
    }

    function setDepartamentoInstrutor($departamentoInstrutor){
        $this->departamentoInstrutor = $departamentoInstrutor;
    }

    function setStatusInstrutor($statusInstrutor){
        $this->statusInstrutor = $statusInstrutor;
    }

    function getIdInstrutor(){
        return $this->idInstrutor;
    }

    function getNomeInstrutor(){
        return $this->nomeInstrutor;
    }

    function getDepartamentoInstrutor(){
        return $this->departamentoInstrutor;
    }

    function getStatusInstrutor(){
        return $this->statusInstrutor;
    }

    /**
     * ID do instrutor
     * Nome do instrutor
     * Departamento do instrutor
     */

    function __toString(){
        return "<br>ID do Instrutor: ".$this->getIdInstrutor()
                ."<br> Nome do instrutor: ".$this->getNomeInstrutor()
                ."<br> Departamento do instrutor: ".$this->getDepartamentoInstrutor()
                ."<br> Status do instrutor: ".$this->getStatusInstrutor()."<br>";
    }

}

//teste da classe e construtor
//$instrutorTeste = new Instrutor(1, "Instrutor teste", 1, 1);

//teste dos metodos get e toString
//echo $instrutorTeste;

//teste dos meteodos set por amostragem
//$instrutorTeste->setNomeInstrutor("Instrutor teste 2");

//echo $instrutorTeste;
?>