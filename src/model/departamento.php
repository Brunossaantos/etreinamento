<?php

/**
 * Classe de domínio Departamento.
 * Representa a entidade de departamentos do sistema,
 * sendo utilizada pelos DAOs para mapeamento direto do banco de dados.
 */

if (isset($_GET['departamento'])) {
    // Captura parâmetro vindo via URL (GET)
    // Possivelmente usado para filtros ou pré-seleção em telas
    $departamentoUrl = $_GET['departamento'];
} else {
    // Caso não exista parâmetro, define como nulo
    $departamento = null;
}

/**
 * ALERTA:
 * Este trecho fora de uma classe (GET direto no model) quebra o princípio de separação de responsabilidades (MVC).
 * Idealmente, leitura de $_GET deveria estar em Controller/View, não no Model.
 */

class Departamento
{
    private $idDepartamento;
    private $nomeDepartamento;
    private $statusDepartamento;

    function __construct($idDepartamento, $nomeDepartamento, $statusDepartamento)
    {
        /**
         * Regra de inicialização:
         * O objeto já nasce completamente populado com os dados do banco.
         * Isso garante consistência do estado da entidade.
         */
        $this->setIdDepartamento($idDepartamento);
        $this->setNomeDepartamento($nomeDepartamento);
        $this->setStatusDepartamento($statusDepartamento);
    }

    // ================= SETTERS =================
    // Encapsulam a alteração dos dados internos da entidade

    function setIdDepartamento($idDepartamento)
    {
        $this->idDepartamento = $idDepartamento;
    }

    function setNomeDepartamento($nomeDepartamento)
    {
        $this->nomeDepartamento = $nomeDepartamento;
    }

    function setStatusDepartamento($statusDepartamento)
    {
        $this->statusDepartamento = $statusDepartamento;
    }

    // ================= GETTERS =================
    // Exposição controlada dos dados da entidade

    function getIdDepartamento()
    {
        return $this->idDepartamento;
    }

    function getNomeDepartamento()
    {
        return $this->nomeDepartamento;
    }

    function getStatusDepartamento()
    {
        return $this->statusDepartamento;
    }

    /**
     * Representação textual do objeto.
     * Usado principalmente para debug e validação rápida em desenvolvimento.
     */
    function __toString()
    {
        return
            "<br>ID do departamento: " . $this->getIdDepartamento()
            . "<br> Nome do departamento: " . $this->getNomeDepartamento()
            . "<br> Status do departamento: " . $this->getStatusDepartamento() . "<br>";
    }
}