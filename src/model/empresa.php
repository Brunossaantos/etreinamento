<?php

/**
 * Classe de domínio Empresa.
 * Representa a entidade Empresa no sistema e é utilizada pelos DAOs
 * para encapsular dados vindos do banco de forma estruturada.
 */
class Empresa
{

    private $idEmpresa;
    private $nomeEmpresa;
    private $statuEmpresa;

    function __construct($idEmpresa, $nomeEmpresa, $statusEmpresa)
    {
        /**
         * Regra de inicialização:
         * O objeto Empresa já é criado totalmente populado,
         * garantindo consistência dos dados desde a instância.
         */
        $this->setIdEmpresa($idEmpresa);
        $this->setNomeEmpresa($nomeEmpresa);
        $this->setStatusEmpresa($statusEmpresa);
    }

    // ================= SETTERS =================
    // Responsáveis por encapsular a atribuição de valores internos

    function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }

    function setNomeEmpresa($nomeEmpresa)
    {
        $this->nomeEmpresa = $nomeEmpresa;
    }

    function setStatusEmpresa($statusEmpresa)
    {
        // Armazena o status de ativação/inativação da empresa
        $this->statuEmpresa = $statusEmpresa;
    }

    // ================= GETTERS =================
    // Responsáveis por expor os dados de forma controlada

    function getIdEmpresa()
    {
        return $this->idEmpresa;
    }

    function getNomeEmpresa()
    {
        return $this->nomeEmpresa;
    }

    function getStatusEmpresa()
    {
        return $this->statuEmpresa;
    }

    /*
     * OBSERVAÇÃO:
     * A variável "statuEmpresa" possui possível inconsistência de nome
     * (provavelmente deveria ser "statusEmpresa").
     * Não foi alterado para não quebrar a lógica existente.
     */

    /**
     * Representação textual da entidade.
     * Usado principalmente para debug e inspeção rápida dos dados.
     */
    function __toString()
    {
        return "<br>ID da empresa: " . $this->getIdEmpresa()
            . "<br> Nome da empresa: " . $this->getNomeEmpresa() . "<br>";
    }
}

/*
Bloco de testes comentado (uso apenas em desenvolvimento)

$empresaTeste = new Empresa(1, "Empresa teste");
echo $empresaTeste;

$empresaTeste->setNomeEmpresa("Empresa teste 2");
echo $empresaTeste;
*/