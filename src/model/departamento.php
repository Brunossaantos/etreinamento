<?php
          
    if(isset($_GET['departamento'])){
        $departamentoUrl = $_GET['departamento'];
    } else {
        $departamento = null;
    }

    class Departamento {
        private $idDepartamento;
        private $nomeDepartamento;
        private $statusDepartamento;
        
        function __construct($idDepartamento, $nomeDepartamento, $statusDepartamento){
            $this->setIdDepartamento($idDepartamento);
            $this->setNomeDepartamento($nomeDepartamento);
            $this->setStatusDepartamento($statusDepartamento);
        }

        function setIdDepartamento($idDepartamento){
            $this->idDepartamento = $idDepartamento;
        }

        function setNomeDepartamento($nomeDepartamento){
            $this->nomeDepartamento = $nomeDepartamento;
        }        

        function setStatusDepartamento($statusDepartamento){
            $this->statusDepartamento = $statusDepartamento;
        }

        function getIdDepartamento(){
            return $this->idDepartamento;
        }

        function getNomeDepartamento(){
            return $this->nomeDepartamento;
        }

        function getStatusDepartamento(){
            return $this->statusDepartamento;
        }
        
        function __toString(){
            return 
            "<br>ID do departamento: ". $this->getIdDepartamento()
            ."<br> Nome do departamento: ". $this->getNomeDepartamento()
            ."<br> Status do departamento: ". $this->getStatusDepartamento()."<br>";        
        }

    }



?>