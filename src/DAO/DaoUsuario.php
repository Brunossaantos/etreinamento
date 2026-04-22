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
    function adicionarUsuario($login, $nome, $email, $senha)
    {
        $statusUsuario = 1;

        $stmt = $this->conexao->prepare("
            INSERT INTO {$this->TBL_USUARIOS} 
            (LOGIN, NOME, EMAIL, STATUS_USUARIO, SENHA_HASH) 
            VALUES (?,?,?,?,?)
        ");

        // ALERTA: A senha deve ser armazenada como hash seguro (ex: password_hash).
        // Se estiver chegando texto puro aqui, há risco de segurança.
        $stmt->bind_param("sssis", $login, $nome, $email, $statusUsuario, $senha);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Consulta um usuário pelo ID.
     * Retorna objeto Usuario com todos os dados carregados do banco.
     */
    function consultarUsuario($idUsuario)
    {

        $login = null;
        $senha = null;
        $nome = null;
        $email = null;
        $statusUsuario = null;

        $stmt = $this->conexao->prepare("
            SELECT * 
            FROM {$this->TBL_USUARIOS} 
            WHERE ID_USUARIO = ?
        ");

        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        // Mapeamento direto dos campos retornados pelo SELECT
        $stmt->bind_result($idUsuario, $login, $nome, $email, $statusUsuario, $senha);
        $stmt->fetch();

        $stmt->close();

        if ($idUsuario) {
            return new Usuario($idUsuario, $login, $senha, $nome, $email, $statusUsuario);
        } else {
            return null;
        }
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
            SET SENHA_HASH = ? 
            WHERE ID_USUARIO = ?
        ");

        $stmt->bind_param("si", $hashNovaSenha, $idUsuario);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
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
            $usuario = new Usuario($idUsuario, $login, $senha, $nome, $email, $statusUsuario);
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