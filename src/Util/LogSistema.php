<?php

class LogSistema
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function registrar($tipoLog, $acao, $detalhes = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idUsuario = $_SESSION['user_id'] ?? null;
        $nomeUsuario = $_SESSION['nome'] ?? 'Sistema';

        $stmt = $this->conn->prepare("
            INSERT INTO LOGS
            (
                TIPO_LOG,
                ACAO,
                DETALHES,
                ID_USUARIO,
                NOME_USUARIO
            )
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssis",
            $tipoLog,
            $acao,
            $detalhes,
            $idUsuario,
            $nomeUsuario
        );

        return $stmt->execute();
    }
    public function registrarErro($mensagem)
{
    $this->registrar(
        'erro',
        'Erro no sistema',
        $mensagem
    );
}
}