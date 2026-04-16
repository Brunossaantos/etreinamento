<?php

class Colaborador
{

    private $idColaborador;
    private $nomeColaborador;

    private $idEmpresa;            // 🔵 legado (ID)
    private $empresaTexto;         // 🟢 gestor (FILIAL)

    private $cargo;
    private $crachaColaborador;
    private $matriculaColaborador;

    private $idDepartamento;       // 🔵 legado (ID)
    private $departamentoTexto;    // 🟢 gestor (DEPTO)

    private $statusColaborador;

    function __construct(
        $idColaborador,
        $nomeColaborador,
        $empresa, // pode ser ID ou TEXTO
        $cargo,
        $crachaColaborador,
        $matriculaColaborador,
        $departamento, // pode ser ID ou TEXTO
        $statusColaborador
    ) {
        $this->setIdColaborador($idColaborador);
        $this->setNomeColaborador($nomeColaborador);

        // 🔥 Empresa (ID ou TEXTO)
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

        // 🔥 Departamento (ID ou TEXTO)
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

    // 🔥 NOVOS (GESTOR)
    function getEmpresaTexto()
    {
        return $this->empresaTexto;
    }

    function getDepartamentoTexto()
    {
        return $this->departamentoTexto;
    }

    // 🔁 COMPATIBILIDADE (RETORNA O QUE EXISTIR)
    function getIdEmpresaColaborador()
    {
        return $this->empresaTexto ?? $this->idEmpresa;
    }

    function getDepartamentoColaborador()
    {
        return $this->departamentoTexto ?? $this->idDepartamento;
    }

    // ================= DEBUG =================

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
