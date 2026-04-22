<?php

/**
 * Classe de domínio Colaborador.
 * Representa a entidade principal de colaboradores no sistema,
 * suportando compatibilidade entre estrutura legada (IDs) e nova estrutura (texto).
 */
class Colaborador
{

    private $idColaborador;
    private $nomeColaborador;

    private $idEmpresa;            // legado (ID da empresa)
    private $empresaTexto;         // modelo gestor (nome da empresa)

    private $cargo;
    private $crachaColaborador;
    private $matriculaColaborador;

    private $idDepartamento;       // legado (ID do departamento)
    private $departamentoTexto;    // modelo gestor (nome do departamento)

    private $statusColaborador;

    function __construct(
        $idColaborador,
        $nomeColaborador,
        $empresa, // pode ser ID ou TEXTO dependendo da origem dos dados
        $cargo,
        $crachaColaborador,
        $matriculaColaborador,
        $departamento, // pode ser ID ou TEXTO dependendo da origem dos dados
        $statusColaborador
    ) {
        $this->setIdColaborador($idColaborador);
        $this->setNomeColaborador($nomeColaborador);

        /**
         * Regra de negócio:
         * O sistema suporta duas fontes de dados (legado e gestor).
         * Se for numérico, trata como ID (modelo antigo).
         * Se não for numérico, trata como texto (modelo novo).
         */

        // Empresa: define se vem como ID ou nome textual
        if (is_numeric($empresa)) {
            $this->idEmpresa = $empresa;
            $this->empresaTexto = null;
        } else {
            $this->empresaTexto = $empresa;
            $this->idEmpresa = null;
        }

        $this->setCargo($cargo);
        $this->setCrachaColaborador($crachaColaborador);
        $this->setMatriculaColaborador($matriculaColaborador);

        // Departamento: define se vem como ID ou nome textual
        if (is_numeric($departamento)) {
            $this->idDepartamento = $departamento;
            $this->departamentoTexto = null;
        } else {
            $this->departamentoTexto = $departamento;
            $this->idDepartamento = null;
        }

        $this->setStatusColaborador($statusColaborador);
    }

    // ================= SETTERS =================
    // Responsáveis por encapsular a atribuição de valores internos da entidade

    function setIdColaborador($idColaborador)
    {
        $this->idColaborador = $idColaborador;
    }

    function setNomeColaborador($nomeColaborador)
    {
        $this->nomeColaborador = $nomeColaborador;
    }

    function setIdEmpresa($idEmpresa)
    {
        $this->idEmpresa = $idEmpresa;
    }

    function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }

    function setCrachaColaborador($crachaColaborador)
    {
        $this->crachaColaborador = $crachaColaborador;
    }

    function setMatriculaColaborador($matriculaColaborador)
    {
        $this->matriculaColaborador = $matriculaColaborador;
    }

    function setIdDepartamento($idDepartamento)
    {
        $this->idDepartamento = $idDepartamento;
    }

    function setStatusColaborador($statusColaborador)
    {
        $this->statusColaborador = $statusColaborador;
    }

    // ================= GETTERS =================
    // Responsáveis por expor os dados da entidade de forma controlada

    function getIdColaborador()
    {
        return $this->idColaborador;
    }

    function getNomeColaborador()
    {
        return $this->nomeColaborador;
    }

    function getCargo()
    {
        return $this->cargo;
    }

    function getCrachaColaborador()
    {
        return $this->crachaColaborador;
    }

    function getMatriculadoColaborador()
    {
        return $this->matriculaColaborador;
    }

    function getStatusColaborador()
    {
        return $this->statusColaborador;
    }

    // ================= CAMPOS NOVOS (MODELO GESTOR) =================
    // Utilizados quando os dados vêm com nome textual em vez de IDs

    function getEmpresaTexto()
    {
        return $this->empresaTexto;
    }

    function getDepartamentoTexto()
    {
        return $this->departamentoTexto;
    }

    /**
     * Compatibilidade híbrida:
     * Retorna o valor da empresa priorizando o modelo textual (gestor),
     * e fallback para ID caso necessário.
     */
    function getIdEmpresaColaborador()
    {
        return $this->empresaTexto ?? $this->idEmpresa;
    }

    /**
     * Compatibilidade híbrida:
     * Retorna o departamento no formato disponível (texto ou ID).
     */
    function getDepartamentoColaborador()
    {
        return $this->departamentoTexto ?? $this->idDepartamento;
    }

    // ================= DEBUG =================

    /**
     * Representação textual do objeto para debug.
     * Útil para validação rápida de dados em desenvolvimento.
     */
    function __toString()
    {
        return "<br>ID Colaborador: " . $this->getIdColaborador()
            . "<br> Nome do Colaborador: " . $this->getNomeColaborador()
            . "<br> Empresa: " . $this->getIdEmpresaColaborador()
            . "<br> Cargo do colaborador: " . $this->getCargo()
            . "<br> Hexadecimal do cracha: " . $this->getCrachaColaborador()
            . "<br> Matricula do colaborador: " . $this->getMatriculadoColaborador()
            . "<br> Departamento do colaborador: " . $this->getDepartamentoColaborador()
            . "<br> Status Colaborador: " . $this->getStatusColaborador() . "<br>";
    }
}