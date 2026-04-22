<?php

/**
 * Classe de domínio Instrutor.
 * Representa a entidade Instrutor no sistema e é utilizada pelo DAO
 * para mapear dados vindos do banco de forma estruturada.
 */
class Instrutor
{

    private $idInstrutor;
    private $nomeInstrutor;
    private $departamentoInstrutor;
    private $statusInstrutor;

    function __construct($idInstrutor, $nomeInstrutor, $departamentoInstrutor, $statusInstrutor)
    {
        /**
         * Regra de inicialização:
         * O instrutor já é instanciado com todos os dados obrigatórios,
         * garantindo consistência do objeto desde sua criação.
         */
        $this->setIdIsntrutor($idInstrutor);
        $this->setNomeInstrutor($nomeInstrutor);
        $this->setDepartamentoInstrutor($departamentoInstrutor);
        $this->setStatusInstrutor($statusInstrutor);
    }

    // ================= SETTERS =================
    // Encapsulam a atribuição dos dados internos da entidade

    function setIdIsntrutor($idInstrutor)
    {
        /**
         * ALERTA:
         * Há um possível erro de digitação no nome do método (Isntrutor).
         * Não corrigido para evitar quebra de dependências no sistema.
         */
        $this->idInstrutor = $idInstrutor;
    }

    function setNomeInstrutor($nomeInstrutor)
    {
        $this->nomeInstrutor = $nomeInstrutor;
    }

    function setDepartamentoInstrutor($departamentoInstrutor)
    {
        $this->departamentoInstrutor = $departamentoInstrutor;
    }

    function setStatusInstrutor($statusInstrutor)
    {
        $this->statusInstrutor = $statusInstrutor;
    }

    // ================= GETTERS =================
    // Exposição controlada dos dados da entidade

    function getIdInstrutor()
    {
        return $this->idInstrutor;
    }

    function getNomeInstrutor()
    {
        return $this->nomeInstrutor;
    }

    function getDepartamentoInstrutor()
    {
        return $this->departamentoInstrutor;
    }

    function getStatusInstrutor()
    {
        return $this->statusInstrutor;
    }

    /**
     * Representação textual do objeto.
     * Usada principalmente para debug e validação rápida em desenvolvimento.
     */
    function __toString()
    {
        return "<br>ID do Instrutor: " . $this->getIdInstrutor()
            . "<br> Nome do instrutor: " . $this->getNomeInstrutor()
            . "<br> Departamento do instrutor: " . $this->getDepartamentoInstrutor()
            . "<br> Status do instrutor: " . $this->getStatusInstrutor() . "<br>";
    }
}

/*
Bloco de testes comentado (uso apenas em desenvolvimento)

$instrutorTeste = new Instrutor(1, "Instrutor teste", 1, 1);
echo $instrutorTeste;

$instrutorTeste->setNomeInstrutor("Instrutor teste 2");
echo $instrutorTeste;
*/