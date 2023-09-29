<?php   

class Empresa {

    private $idEmpresa;
    private $nomeEmpresa;
    private $statuEmpresa;

    function __construct($idEmpresa, $nomeEmpresa, $statusEmpresa){
        $this->setIdEmpresa($idEmpresa);
        $this->setNomeEmpresa($nomeEmpresa);
        $this->setStatusEmpresa($statusEmpresa);
    }

    function setIdEmpresa($idEmpresa){
        $this->idEmpresa = $idEmpresa;
    }
    
    function setNomeEmpresa($nomeEmpresa){
        $this->nomeEmpresa = $nomeEmpresa;
    }

    function setStatusEmpresa($statusEmpresa){
        $this->statuEmpresa = $statusEmpresa;
    }

    function getIdEmpresa(){
        return $this->idEmpresa;
    }

    function getNomeEmpresa(){
        return $this->nomeEmpresa;
    }

    function getStatusEmpresa(){
        return $this->statuEmpresa;
    }

    /*
    ID empresa:
    Nome da Empresa:
    */

    function __toString(){
        return "<br>ID da empresa: ".$this->getIdEmpresa()
                ."<br> Nome da empresa: ".$this->getNomeEmpresa()."<br>"; 
    }

}

//teste de classe e construtores
//$empresaTeste = new Empresa(1, "Empresa teste");

//teste de metodos get e toString
//echo $empresaTeste;

//teste de metodos set por amostragem

//$empresaTeste->setNomeEmpresa("Empresa teste 2");
//echo $empresaTeste;

?>