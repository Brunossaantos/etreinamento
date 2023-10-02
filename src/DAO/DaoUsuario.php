<?php 

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../model/usuario.php');

class DaoUsuario{
    private $TBL_USUARIOS = "usuarios";
    private $conexao;

    function __construct($conexao){
        $this->conexao=$conexao;
    }

    function adicionarUsuario($login, $senha, $nome, $email){
        $statusUsuario = 1;

        $stmt = $this->conexao->prepare("INSERT INTO {$this->TBL_USUARIOS} (LOGIN, SENHA, NOME, EMAIL, STATUS_USUARIO) VALUES (?,?,?,?,?)");
        $stmt->bind_param("ssssi", $login, $senha, $nome, $email, $statusUsuario);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function consultarUsuario($idUsuario){

        $login = null;
        $senha = null;
        $nome = null;
        $email = null;
        $statusUsuario = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_USUARIOS} WHERE ID_USUARIO = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $stmt->bind_result($idUsuario, $login, $nome, $email, $statusUsuario, $senha);
        $stmt->fetch();

        $stmt->close();

        if($idUsuario){            
            return new Usuario($idUsuario, $login, $senha, $nome, $email, $statusUsuario);
        } else {
            return null;
        }
    }

    function atuaizarUsuario($idUsuario, $login, $senha, $nome, $email, $statusUsuario){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_USUARIOS} SET LOGIN = ?, SENHA = ?, NOME = ?, EMAIL = ?, STATUS_USUARIO = ? WHERE ID_USUARIO = ?");
        $stmt->bind_param("ssssii", $login, $senha, $nome, $email, $statusUsuario, $idUsuario);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function alterarStatus($idUsuario, $statusUsuario){
        $stmt = $this->conexao->prepare("UPDATE {$this->TBL_USUARIOS} SET STATUS_USUARIO = ? WHERE ID_USUARIO = ?");
        $stmt->bind_param("ii", $statusUsuario, $idUsuario);

        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }

    function gerarListaUsuario(){
        $listaUsuarios = [];
        $idUsuario = null;
        $login = null;
        $senha = null;
        $nome = null;
        $email = null;
        $statusUsuario = null;

        $stmt = $this->conexao->prepare("SELECT * FROM {$this->TBL_USUARIOS}");
        $stmt->execute();
        $stmt->bind_result($idUsuario, $login, $senha, $nome, $email, $statusUsuario);

        while($stmt->fetch()){
            $usuario = new Usuario($idUsuario, $login, $senha, $nome, $email, $statusUsuario);
            $listaUsuarios[] = $usuario;
        } 

        $stmt->close();
        return $listaUsuarios;
    }

}

/*
$conexao = new Conexao();
$daoUsuario = new DaoUsuario($conexao->conectar());

$listagemDeUsuarios = $daoUsuario->gerarListaUsuario();
foreach($listagemDeUsuarios as $usuario){
    echo $usuario;
}
*/
?>