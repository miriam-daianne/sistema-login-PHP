<?php
session_start();
require_once 'config/conexao.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: painel.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    if ($user_id == $_SESSION['user_id']) {
        $_SESSION['erro'] = "Você não pode excluir sua própria conta.";
        header("Location: painel.php");
        exit;
    }
    
    $stmt = $conexao->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Usuário excluído com sucesso!";
    } else {
        $_SESSION['erro'] = "Erro ao excluir usuário.";
    }
    $stmt->close();
}

header("Location: painel.php");
exit;
?>
