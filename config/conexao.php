<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'sistema_login';

$conexao = new mysqli($host, $usuario, $senha);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$conexao->query("CREATE DATABASE IF NOT EXISTS $banco");

$conexao->select_db($banco);

$sql_file = __DIR__ . '/criar_banco.sql';
if (file_exists($sql_file)) {
    $sql = file_get_contents($sql_file);
    if ($conexao->multi_query($sql)) {
        do {
            if ($result = $conexao->store_result()) {
                $result->free();
            }
        } while ($conexao->next_result());
    } else {
        error_log("[SUCCESS] Login realizado por: " . $email . " - IP: " . $_SERVER['REMOTE_ADDR']);
        error_log("[FAILED] Tentativa de login com: " . $email . " - IP: " . $_SERVER['REMOTE_ADDR']);
        die("Erro ao executar o script SQL: " . $conexao->error);
    }
} else {
    die("Arquivo criar_banco.sql não encontrado.");
}
?>