<?php

require_once __DIR__ . '/../config/env.php';

/**
 * Classe responsável pela conexão com o banco de dados MySQL.
 * Utiliza variáveis de ambiente para manter credenciais seguras e separadas do código.
 */
class Conexao
{
    private $conn;

    /**
     * Cria e retorna uma conexão ativa com o banco de dados.
     * Utiliza mysqli com parâmetros vindos do arquivo de ambiente (.env).
     */
    function conectar()
    {

        $this->conn = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_NAME']
        );

        // Validação crítica da conexão com o banco
        if ($this->conn->connect_error) {
            // ALERTA: die interrompe toda execução do sistema e pode expor detalhes sensíveis do servidor
            die("Erro na conexão com o banco de dados: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

    /**
     * Encerra a conexão ativa com o banco de dados.
     * Usado para liberar recursos após operações finalizadas.
     */
    function fecharConexao()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    /**
     * Retorna informações básicas da conexão.
     * Usado principalmente para debug e validação de ambiente.
     */
    function __toString()
    {
        return "Servidor: " . $_ENV['DB_HOST']
            . "<br>Usuário: " . $_ENV['DB_USER']
            . "<br>Banco de dados: " . $_ENV['DB_NAME'] . "<br>";
    }
}
