<?php
session_start();

require_once(__DIR__ . '/../database/conexao.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php");
    exit();
}

$token = $_POST['token'] ?? '';
$novaSenha = $_POST['nova_senha'] ?? '';
$confirmarSenha = $_POST['confirmar_senha'] ?? '';

if (empty($token) || empty($novaSenha) || empty($confirmarSenha)) {
    header("Location: ../../index.php");
    exit();
}

if ($novaSenha !== $confirmarSenha) {
    header("Location: ../../redefinirSenha.php?token=" . urlencode($token) . "&erro=senhas_diferentes");
    exit();
}

if (strlen($novaSenha) < 6) {
    header("Location: ../../redefinirSenha.php?token=" . urlencode($token) . "&erro=senha_curta");
    exit();
}

$conn = (new Conexao())->conectar();

$stmt = $conn->prepare("
    SELECT ID_USUARIO, token_expira
    FROM usuarios
    WHERE token_reset = ?
    LIMIT 1
");

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    header("Location: ../../index.php?erro=token_invalido");
    exit();
}

if (strtotime($usuario['token_expira']) < time()) {
    header("Location: ../../esqueciSenha.php?erro=token_expirado");
    exit();
}

$senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    UPDATE usuarios
    SET 
        SENHA_HASH = ?,
        primeiro_acesso = 0,
        token_reset = NULL,
        token_expira = NULL
    WHERE ID_USUARIO = ?
");

$stmt->bind_param("si", $senhaHash, $usuario['ID_USUARIO']);

if ($stmt->execute()) {
    header("Location: ../../index.php?sucesso=senha_redefinida");
    exit();
}

header("Location: ../../redefinirSenha.php?token=" . urlencode($token) . "&erro=erro_ao_salvar");
exit();
