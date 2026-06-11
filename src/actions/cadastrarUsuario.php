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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../layout/cadastrarUsuario.php");
    exit();
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$login = trim($_POST['login'] ?? '');
$senha = $_POST['senha'] ?? '';
$status = (int) ($_POST['status'] ?? 1);
$perfil = (int) ($_POST['perfil'] ?? 2);

if (empty($nome) || empty($email) || empty($login) || empty($senha)) {
    header("Location: ../../layout/cadastrarUsuario.php?erro=dados_invalidos");
    exit();
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$conexao = new Conexao();
$conn = $conexao->conectar();
    
$logSistema = new LogSistema($conn);

$stmt = $conn->prepare("
    INSERT INTO usuarios
    (
        LOGIN,
        NOME,
        EMAIL,
        STATUS_USUARIO,
        SENHA_HASH,
        primeiro_acesso,
        PERFIL
    )
    VALUES (?, ?, ?, ?, ?, 1, ?)
");

$stmt->bind_param(
    "sssisi",
    $login,
    $nome,
    $email,
    $status,
    $senhaHash,
    $perfil
);

if ($stmt->execute()) {

    $logSistema->registrar(
        'usuario',
        'Usuário cadastrado',
        "Usuário cadastrado: {$nome} | Login: {$login} | Perfil: {$perfil}"
    );

    header("Location: ../../layout/cadastrarUsuario.php?sucesso=cadastrado");
    exit();
}

header("Location: ../../layout/cadastrarUsuario.php?erro=erro_ao_cadastrar");
exit();