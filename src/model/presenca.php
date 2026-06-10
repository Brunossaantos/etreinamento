<?php

/**
 * Classe de domínio Presença.
 * Representa o registro de presença de um colaborador em um treinamento.
 */
class Presenca
{
    private $idTreinamento;
    private $idColaborador;
    private $horaPresenca;
    private $origemColaborador;

    function __construct($idTreinamento, $idColaborador, $horaPresenca, $origemColaborador = 'etreinamento')
    {
        $this->setIdTreinamento($idTreinamento);
        $this->setIdColaborador($idColaborador);
        $this->setHoraPresenca($horaPresenca);
        $this->setOrigemColaborador($origemColaborador);
    }

    // ================= SETTERS =================

    function setIdTreinamento($idTreinamento)
    {
        $this->idTreinamento = $idTreinamento;
    }

    function setIdColaborador($idColaborador)
    {
        $this->idColaborador = $idColaborador;
    }

    function setHoraPresenca($horaPresenca)
    {
        $this->horaPresenca = $horaPresenca;
    }

    function setOrigemColaborador($origemColaborador)
    {
        $this->origemColaborador = $origemColaborador;
    }

    // ================= GETTERS =================

    function getIdTreinamento()
    {
        return $this->idTreinamento;
    }

    function getIdColaborador()
    {
        return $this->idColaborador;
    }

    function getHoraPresenca()
    {
        return $this->horaPresenca;
    }

    function getOrigemColaborador()
    {
        return $this->origemColaborador;
    }

    function dataAtual()
    {
        return date('d-m-Y H:i:s');
    }

    function __toString()
    {
        return "<br>ID do treinamento: " . $this->getIdTreinamento()
            . "<br>ID do colaborador: " . $this->getIdColaborador()
            . "<br>Horário da presença: " . $this->getHoraPresenca()
            . "<br>Origem do colaborador: " . $this->getOrigemColaborador() . "<br>";
    }
}