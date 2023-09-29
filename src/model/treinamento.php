<?php   

class Treinamento {
    private $idTreinamento;
    private $descricaoTreinamento;
    private $dataTreinamento;
    // private $horarioTreinamento;
    private $instrutor;
    private $departamento;
    private $statusTreinamento;
    private $conteudoTreinamento;
    private $cargaHoraria;
    private $localTreinamento;

    function __construct($idTreinamento, $descricaoTreinamento, $dataTreinamento, /*$horarioTreinamento, */$instrutor, $departamento, $statusTreinamento, $conteudoTreinamento, $cargaHoraria, $local){
        $this->setIdTreinamento($idTreinamento);
        $this->setDescricaoTreinamento($descricaoTreinamento);
        $this->setDataTreinamento($dataTreinamento);
        //$this->setHorarioTreinamento($horarioTreinamento);
        $this->setInstrutor($instrutor);
        $this->setDepartamento($departamento);
        $this->setStatusTreinamento($statusTreinamento);
        $this->setConteudoTreinamento($conteudoTreinamento);
        $this->setCargaHoraria($cargaHoraria);
        $this->setLocalTreinamento($local);
    }

    function setIdTreinamento($idTreinamento){
        $this->idTreinamento = $idTreinamento;
    }

    function setDescricaoTreinamento($descricaoTreinamento){
        $this->descricaoTreinamento = $descricaoTreinamento;
    }

    function setDataTreinamento($dataTreinamento){
        $this->dataTreinamento = $dataTreinamento;
    }
    
    // function setHorarioTreinamento($horarioTreinamento){
    //     $this->horarioTreinamento = $horarioTreinamento;
    // }

    function setInstrutor($instrutor){
        $this->instrutor=$instrutor;
    }

    function setDepartamento($departamento){
        $this->departamento= $departamento;
    }

    function setStatusTreinamento($statusTreinamento){
        $this->statusTreinamento =$statusTreinamento;
    }

    function setConteudoTreinamento($conteudoTreinamento){
        $this->conteudoTreinamento = $conteudoTreinamento;
    }

    function setCargaHoraria($cargaHoraria){
        $this->cargaHoraria = $cargaHoraria;
    }
    function setLocalTreinamento($local){
        $this->localTreinamento = $local;
    }

    function getIdTreinamento(){
        return $this->idTreinamento;
    }

    function getDescricaoTreinamento(){
        return $this->descricaoTreinamento;
    }

    function getDataTreinamento(){
        return $this->dataTreinamento;
    }

    // function getHorarioTreinamento(){
    //     return $this->horarioTreinamento;
    // }

    function getInstrutor(){
        return $this->instrutor;
    }

    function getDepartamento(){
        return $this->departamento;
    }

    function getStatusTreinamento(){
        return $this->statusTreinamento;
    }

    function getConteudoTreinamento(){
        return $this->conteudoTreinamento;
    }
    
    function getCargaHoraria(){
        return $this->cargaHoraria;
    }

    function getLocalTreinamento(){
        return $this->localTreinamento;
    }

    /**
     * ID do treinamento
     * Descrição do treinamento
     * Data do treinamento
     * Horario do treinamento
     * Instrutor
     * Departamento
     * Status do treinamento
     * Conteudo do treinamento
     * Carga horária do treinamento
     * Local do treinamento
     */
    
    function __toString(){
        return "<br>ID do treinamento: ".$this->getIdTreinamento()
        ."<br> Descrição do treinamento: ".$this->getDescricaoTreinamento()
        ."<br> Data do treinamento: ".$this->getDataTreinamento()
        // ."<br> Horário do treinamento: ".$this->getHorarioTreinamento()
        ."<br> Instrutor: ".$this->getInstrutor()
        ."<br> Departamento: ".$this->getDepartamento()
        ."<br> Status do treinamento: ".$this->getStatusTreinamento()
        ."<br> Conteúdo do treinamento: ".$this->getConteudoTreinamento()
        ."<br> Carga horária: ".$this->getCargaHoraria()
        ."<br> Local do treinamento ".$this->getLocalTreinamento()."<br>";
    }
}


?>