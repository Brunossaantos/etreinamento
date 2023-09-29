<?php   

class Colaborador{

    private $idColaborador;
    private $nomeColaborador;
    private $idEmpresa;
    private $cargo;
    private $crachaColaborador;
    private $matriculaColaborador;
    private $idDepartamento;
    private $statusColaborador;
    
    function __construct($idColaborador, $nomeColaborador, $idEmpresa, $cargo, $crachaColaborador, $matriculaColaborador, $idDepartamento, $statusColaborador){
        $this->setIdColaborador($idColaborador);
        $this->setNomeColaborador($nomeColaborador);
        $this->setIdEmpresa($idEmpresa);
        $this->setCargo($cargo);
        $this->setCrachaColaborador($crachaColaborador);
        $this->setMatriculaColaborador($matriculaColaborador);
        $this->setIdDepartamento($idDepartamento);
        $this->setStatusColaborador($statusColaborador);
    }

    function setIdColaborador($idColaborador){
        $this->idColaborador = $idColaborador;
    }

    function setNomeColaborador($nomeColaborador){
        $this->nomeColaborador = $nomeColaborador;
    }

    function setIdEmpresa($idEmpresa){
        $this->idEmpresa = $idEmpresa;
    }

    function setCargo($cargo){
        $this->cargo = $cargo;
    }

    function setCrachaColaborador($crachaColaborador){
        $this->crachaColaborador = $crachaColaborador;
    }

    function setMatriculaColaborador($matriculaColaborador){
        $this->matriculaColaborador = $matriculaColaborador;
    }

    function setIdDepartamento($idDepartamento){
        $this->idDepartamento = $idDepartamento;
    }

    function setStatusColaborador($statusColaborador){
        $this->statusColaborador = $statusColaborador;
    }

    function getIdColaborador(){
        return $this->idColaborador;
    }

    function getNomeColaborador(){
        return $this->nomeColaborador;
    }

    function getIdEmpresaColaborador(){
        return $this->idEmpresa;
    }

    function getCargo(){
        return $this->cargo;
    }

    function getCrachaColaborador(){
        return $this->crachaColaborador;
    }

    function getMatriculadoColaborador(){
        return $this->matriculaColaborador;
    }

    function getDepartamentoColaborador(){
        return $this->idDepartamento;
    }

    function getStatusColaborador(){
        return $this->statusColaborador;
    }

    /*
    ID
    Nome
    Empresa
    Cargo
    Cracha
    Matricula
    Departamento
    Status Colaborador
    */
    function __toString(){
        return "<br>ID Colaborador: ".$this->getIdColaborador()
        ."<br> Nome do Colaborador: ".$this->getNomeColaborador()
        ."<br> Empresa: ".$this->getIdEmpresaColaborador()
        ."<br> Cargo do colaborador: ".$this->getCargo()
        ."<br> Hexadecimal do cracha: ".$this->getCrachaColaborador()
        ."<br> Matricula do colaborador: ".$this->getMatriculadoColaborador()
        ."<br> Departamento do colaborador: ".$this->getDepartamentoColaborador()
        ."<br> Status Colaborador: ".$this->getStatusColaborador()."<br>";
    }

}

//teste de classe e construtores
//$colaboradorTeste = new Colaborador(1, "Danilo Franco", 1,"Desenvolvedor", 123456, 54321, 1);

//teste de metodos get e toString
//echo $colaboradorTeste;

//teste do metodo set por amostragem
//$colaboradorTeste->setNomeColaborador("Danilo de Sousa Franco");
//echo $colaboradorTeste;
?>