<?php

/**
 * Classe de domínio PresencaVisitante.
 * Representa registros de presença de visitantes no sistema,
 * normalmente identificados por hexadecimal (crachá ou identificação externa).
 */
class PresencaVisitante
{
    private $hexadecimal;
    private $idTreinamento;
    private $horarioDaPresenca;

    function __construct($idTreinamento, $hexadecimal, $horarioDaPresenca)
    {
        /**
         * Regra de inicialização:
         * O objeto já é criado com todos os dados necessários para registro
         * da presença do visitante em um treinamento.
         */
        $this->setIdTreinamento($idTreinamento);
        $this->setHexadecimal($hexadecimal);
        $this->setHorarioDaPresenca($horarioDaPresenca);
    }

    // ================= SETTERS =================
    // Encapsulam a atribuição dos dados da presença do visitante

    function setIdTreinamento($idTreinamento)
    {
        $this->idTreinamento = $idTreinamento;
    }

    function setHexadecimal($hexadecimal)
    {
        /**
         * Identificador externo (crachá/hexadecimal).
         * Usado para visitantes não cadastrados como colaboradores.
         */
        $this->hexadecimal = $hexadecimal;
    }

    function setHorarioDaPresenca($horarioDaPresenca)
    {
        $this->horarioDaPresenca = $horarioDaPresenca;
    }

    // ================= GETTERS =================
    // Exposição controlada dos dados da presença do visitante

    function getIdTreinamento()
    {
        return $this->idTreinamento;
    }

    function getHexadecimal()
    {
        return $this->hexadecimal;
    }

    function getHorarioDaPresenca()
    {
        return $this->horarioDaPresenca;
    }

    /**
     * Representação textual do objeto.
     * Usado para debug e validação rápida de registros de visitantes.
     */
    function __toString()
    {
        return "ID treinamento: " . $this->getIdTreinamento()
            . "<br>Hexadecimal: " . $this->getHexadecimal()
            . "<br>Horario da presença: " . $this->getHorarioDaPresenca() . "<br>";
    }
}