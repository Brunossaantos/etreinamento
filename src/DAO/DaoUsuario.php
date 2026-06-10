<?php

// include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/usuario.php');

/**
 * DAO responsável pelas operações de banco de dados da entidade Usuário.
 * Controla criação, autenticação básica (dados), atualização e status de usuários do sistema.
 */
class DaoUsuario
{
    private $TBL_USUARIOS = "usuarios";
    private $conexao;

    function __construct($conexao)
    {
        // Conexão ativa com o banco de dados (mysqli)
        $this->conexao = $conexao;
    }

    /**
     * Adiciona um novo usuário no sistema.
     * Regra de negócio: usuário sempre inicia com STATUS_USUARIO = 1 (ativo).
     */
    function adicionarUsuario($login, $nome, $email, $senha, $statusUsuario = 1, $primeiroAcesso = 1)
    {
        $stmt = $this->conexao->prepare("
        INSERT INTO {$this->TBL_USUARIOS} 
        (LOGIN, NOME, EMAIL, STATUS_USUARIO, SENHA_HASH, primeiro_acesso) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

        // tipos:
        // s = string
        // i = integer
        $stmt->bind_param(
            "sssisi",
            $login,
            $nome,
            $email,
            $statusUsuario,
            $senha,
            $primeiroAcesso
        );

        return $stmt->execute();
    }

    /**
     * Consulta um usuário pelo ID.
     * Retorna objeto Usuario com todos os dados carregados do banco.
     */
    function consultarUsuario($idUsuario)
    {
        $stmt = $this->conexao->prepare("
        SELECT 
            ID_USUARIO,
            LOGIN,
            NOME,
            EMAIL,
            STATUS_USUARIO,
            SENHA_HASH,
            primeiro_acesso
        FROM {$this->TBL_USUARIOS}
        WHERE ID_USUARIO = ?
    ");

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        $login = null;
        $senha = null;
        $nome = null;
        $email = null;
        $statusUsuario = null;
        $primeiroAcesso = null;

        $stmt->bind_result(
            $idUsuario,
            $login,
            $nome,
            $email,
            $statusUsuario,
            $senha,
            $primeiroAcesso
        );

        $stmt->fetch();
        $stmt->close();

        if ($idUsuario) {
            return new Usuario(
                $idUsuario,
                $login,
                $senha,
                $nome,
                $email,
                $statusUsuario,
                $primeiroAcesso
            );
        }

        return null;
    }

    /**
     * Atualiza dados principais do usuário (exceto senha).
     * Usado para edição de perfil administrativo.
     */
    function atuaizarUsuario($idUsuario, $login, $nome, $email, $statusUsuario)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_USUARIOS} 
            SET LOGIN = ?, NOME = ?, EMAIL = ?, STATUS_USUARIO = ? 
            WHERE ID_USUARIO = ?
        ");

        $stmt->bind_param("sssii", $login, $nome, $email, $statusUsuario, $idUsuario);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Atualiza a senha do usuário.
     * Deve receber senha já criptografada (hash).
     */
    function alterarSenha($idUsuario, $hashNovaSenha)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_USUARIOS} 
            SET SENHA_HASH = ?, primeiro_acesso = 0 
            WHERE ID_USUARIO = ?
        ");

        $stmt->bind_param("si", $hashNovaSenha, $idUsuario);

        return $stmt->execute();
    }

    /**
     * Altera status do usuário (ativo/inativo).
     * Usado para bloqueio/desativação sem exclusão do registro.
     */
    function alterarStatus($idUsuario, $statusUsuario)
    {
        $stmt = $this->conexao->prepare("
            UPDATE {$this->TBL_USUARIOS} 
            SET STATUS_USUARIO = ? 
            WHERE ID_USUARIO = ?
        ");

        $stmt->bind_param("ii", $statusUsuario, $idUsuario);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retorna lista completa de usuários cadastrados no sistema.
     */
    function gerarListaUsuario()
    {
        $listaUsuarios = [];
        $idUsuario = null;
        $login = null;
        $senha = null;
        $nome = null;
        $email = null;
        $statusUsuario = null;

        $stmt = $this->conexao->prepare("
            SELECT * 
            FROM {$this->TBL_USUARIOS}
        ");

        $stmt->execute();
        $stmt->bind_result($idUsuario, $login, $senha, $nome, $email, $statusUsuario);

        while ($stmt->fetch()) {
            $usuario = new Usuario($idUsuario, $login, $senha, $nome, $email, $statusUsuario, primeiroAcesso: 0);
            $listaUsuarios[] = $usuario;
        }

        $stmt->close();
        return $listaUsuarios;
    }
}

/*
Bloco de teste comentado (uso apenas em desenvolvimento)

$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

$listagemDeUsuarios = $daoUsuario->gerarListaUsuario();
foreach($listagemDeUsuarios as $usuario){
    echo $usuario;
}
*/