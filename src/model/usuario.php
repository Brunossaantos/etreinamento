<?php

/**
 * Classe de domínio Usuario.
 * Representa a entidade de usuários do sistema,
 * responsável por autenticação, controle de acesso e dados básicos do usuário.
 */
class Usuario
{
    private $idUsuario;
    private $loginUsuario;
    private $senha;
    private $nome;
    private $email;
    private $statusUsuario;

    function __construct($idUsuario, $loginUsuario, $senha, $nome, $email, $statusUsuario)
    {
        /**
         * Regra de inicialização:
         * O objeto Usuario já é criado completamente populado,
         * garantindo consistência dos dados desde a instância.
         */
        $this->setIdUsuario($idUsuario);
        $this->setLogin($loginUsuario);
        $this->setSenha($senha);
        $this->setNome($nome);
        $this->setEmail($email);
        $this->setStatusUsuario($statusUsuario);
    }

    // ================= SETTERS =================
    // Encapsulam a atribuição dos dados internos do usuário

    function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    function setLogin($loginUsuario)
    {
        $this->loginUsuario = $loginUsuario;
    }

    function setSenha($senha)
    {
        /**
         * ALERTA:
         * O campo senha está sendo armazenado diretamente no objeto.
         * O ideal em produção é trabalhar sempre com hash (ex: password_hash),
         * evitando exposição de senha em texto puro.
         */
        $this->senha = $senha;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setStatusUsuario($statusUsuario)
    {
        $this->statusUsuario = $statusUsuario;
    }

    // ================= GETTERS =================
    // Exposição controlada dos dados do usuário

    function getIdUsuario()
    {
        return $this->idUsuario;
    }

    function getLogin()
    {
        return $this->loginUsuario;
    }

    function getSenha()
    {
        return $this->senha;
    }

    function getNome()
    {
        return  $this->nome;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getStatusUsuario()
    {
        return $this->statusUsuario;
    }

    /**
     * Representação textual do usuário.
     * Usado principalmente para debug e inspeção rápida de dados.
     */
    function __toString()
    {
        return
            "<br> ID usuario: " . $this->getIdUsuario()
            . "<br> Login Usuario: " . $this->getLogin()
            . "<br> Senha: " . $this->getSenha()
            . "<br> Nome: " . $this->getNome()
            . "<br> Email: " . $this->getEmail()
            . "<br> Status do Usuário: " . $this->getStatusUsuario() . "<br>";
    }
}