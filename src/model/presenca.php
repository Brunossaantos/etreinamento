<?php

/**
 * Classe de domínio Presença.
 * Representa o registro de presença de um colaborador em um treinamento.
 * Usada para mapear dados da tabela de presença no sistema.
 */
class Presenca
{

    private $idTreinamento;
    private $idColaborador;
    private $horaPresenca;

    function __construct($idTreinamento, $idColaborador, $horaPresenca)
    {
        /**
         * Regra de inicialização:
         * A presença já é criada com todos os dados necessários,
         * garantindo consistência do registro no momento do mapeamento.
         */
        $this->setIdTreinamento($idTreinamento);
        $this->setIdColaborador($idColaborador);
        $this->setHoraPresenca($horaPresenca);
    }

    // ================= SETTERS =================
    // Encapsulam a atribuição dos dados da presença

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

    // ================= GETTERS =================
    // Exposição controlada dos dados da presença

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

    /**
     * Retorna a data/hora atual do servidor.
     * OBS: não está ligada diretamente ao registro de presença,
     * pode ser usada apenas para debug ou comparação de horário.
     */
    function dataAtual()
    {
        return date('d-m-Y H:i:s');
    }

    /**
     * Representação textual do objeto.
     * Usada para debug e validação rápida dos registros de presença.
     */
    function __toString()
    {
        return "<br>ID do treinamento: " . $this->getIdTreinamento()
            . "<br> ID do colaborador: " . $this->getIdColaborador()
            . "<br> Horario da presença: " . $this->getHoraPresenca() . "<br>";
    }
}
