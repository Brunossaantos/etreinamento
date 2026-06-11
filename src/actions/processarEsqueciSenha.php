<?php
session_start();

require_once(__DIR__ . '/../database/conexao.php');
require_once(__DIR__ . '/../../config/initEnv.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../esqueciSenha.php");
    exit();
}

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    header("Location: ../../esqueciSenha.php?erro=1");
    exit();
}

$conn = (new Conexao())->conectar();

$stmt = $conn->prepare("
    SELECT ID_USUARIO, NOME, EMAIL
    FROM usuarios
    WHERE EMAIL = ?
    AND STATUS_USUARIO = 1
    LIMIT 1
");

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    header("Location: ../../esqueciSenha.php?sucesso=1");
    exit();
}

$token = bin2hex(random_bytes(32));
$tokenExpira = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $conn->prepare("
    UPDATE usuarios
    SET token_reset = ?, token_expira = ?
    WHERE ID_USUARIO = ?
");

$stmt->bind_param("ssi", $token, $tokenExpira, $usuario['ID_USUARIO']);
$stmt->execute();

$link = "https://localhost/etreinamento/redefinirSenha.php?token=" . urlencode($token);

try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASS'];
    $mail->Port = (int) $_ENV['SMTP_PORT'];
    $mail->CharSet = $_ENV['SMTP_CHARSET'] ?? 'UTF-8';

    if ($_ENV['SMTP_SECURE'] === 'smtps') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } elseif ($_ENV['SMTP_SECURE'] === 'tls') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    $mail->setFrom('suporte.ti@envios.udlog.com.br', 'E-Treinamento');
    $mail->addAddress($usuario['EMAIL'], $usuario['NOME']);

    $mail->isHTML(true);
    $mail->Subject = 'Redefinição de senha - E-Treinamento';

    $nomeUsuario = htmlspecialchars($usuario['NOME'], ENT_QUOTES, 'UTF-8');

    $mail->Body = "
        <p>Olá, <strong>{$nomeUsuario}</strong>.</p>

        <p>Recebemos uma solicitação para redefinir sua senha no sistema E-Treinamento.</p>

        <p>Clique no botão abaixo para cadastrar uma nova senha:</p>

        <p>
            <a href='{$link}'
               style='background:#115391;color:#fff;padding:12px 18px;text-decoration:none;border-radius:8px;display:inline-block;'>
                Redefinir senha
            </a>
        </p>

        <p>Ou copie e cole este link no navegador:</p>

        <p>{$link}</p>

        <p>Este link é válido por 1 hora.</p>

        <p>Se você não solicitou essa alteração, ignore este e-mail.</p>
    ";

    $mail->AltBody = "
Olá, {$usuario['NOME']}.

Recebemos uma solicitação para redefinir sua senha no sistema E-Treinamento.

Acesse o link abaixo para cadastrar uma nova senha:

{$link}

Este link é válido por 1 hora.

Se você não solicitou essa alteração, ignore este e-mail.
";

    $mail->send();

    header("Location: ../../esqueciSenha.php?sucesso=1");
    exit();
} catch (Exception $e) {

    echo "<pre>";
    echo "ERRO:\n\n";
    echo $e->getMessage();
    echo "</pre>";

    exit();
}
