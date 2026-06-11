<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../index.php");
    exit();
}

require_once(__DIR__ . '/../Util/permissoes.php');

if (!isAdmin()) {
    header("Location: ../../index2.php");
    exit();
}

include(__DIR__ . '/../database/conexao.php');
include(__DIR__ . '/../Util/LogSistema.php');

$conn = (new Conexao())->conectar();
$logSistema = new LogSistema($conn);

$idUsuario = $_POST['id_usuario'] ?? null;
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';
$status = (int) ($_POST['status'] ?? 1);
$perfil = (int) ($_POST['perfil'] ?? 2);

if (!$idUsuario || empty($nome) || empty($email) || empty($login)) {
    header("Location: ../../layout/cadastrarUsuario.php?erro=dados_invalidos");
    exit();
}

if (!empty($senha)) {
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        UPDATE usuarios
        SET 
            NOME = ?,
            EMAIL = ?,
            LOGIN = ?,
            SENHA_HASH = ?,
            STATUS_USUARIO = ?,
            PERFIL = ?
        WHERE ID_USUARIO = ?
    ");

    $stmt->bind_param(
        "ssssiii",
        $nome,
        $email,
        $login,
        $senhaHash,
        $status,
        $perfil,
        $idUsuario
    );
} else {

    $stmt = $conn->prepare("
        UPDATE usuarios
        SET 
            NOME = ?,
            EMAIL = ?,
            LOGIN = ?,
            STATUS_USUARIO = ?,
            PERFIL = ?
        WHERE ID_USUARIO = ?
    ");

    $stmt->bind_param(
        "sssiii",
        $nome,
        $email,
        $login,
        $status,
        $perfil,
        $idUsuario
    );
}

if ($stmt->execute()) {

    $logSistema->registrar(
        'usuario',
        'Usuário alterado',
        "Usuário alterado: {$nome} | Login: {$login} | Perfil: {$perfil} | Status: {$status}"
    );

    header("Location: ../../layout/cadastrarUsuario.php?sucesso=atualizado");
    exit();
}

header("Location: ../../layout/editarUsuario.php?id={$idUsuario}&erro=1");
exit();