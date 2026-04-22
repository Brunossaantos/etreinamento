<?php

require_once __DIR__ . '/../config/env.php';

/**
 * Classe responsável pela conexão com o banco de dados do sistema gestor.
 * Essa conexão utiliza um banco separado do sistema principal (DB2_*),
 * normalmente usado para operações administrativas ou de controle.
 */
class ConexaoGestor
{

    private $conn;

    /**
     * Cria e retorna a conexão com o banco de dados do gestor.
     * Utiliza variáveis de ambiente específicas (DB2_*), separando
     * os dados do sistema principal por segurança e organização.
     */
    function conectar()
    {

        $this->conn = new mysqli(
            $_ENV['DB2_HOST'],
            $_ENV['DB2_USER'],
            $_ENV['DB2_PASS'],
            $_ENV['DB2_NAME']
        );

        // Validação crítica da conexão
        if ($this->conn->connect_error) {
            // ALERTA: uso de die interrompe execução do sistema e pode expor detalhes internos do banco
            die("Erro conexão gestor: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}