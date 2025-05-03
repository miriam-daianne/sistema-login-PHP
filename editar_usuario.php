<?php
session_start();
require_once 'config/conexao.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: painel.php");
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    $stmt = $conexao->prepare("SELECT status FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $new_status = $user['status'] == 1 ? 0 : 1;
        
        $update_stmt = $conexao->prepare("UPDATE users SET status = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $new_status, $user_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['mensagem'] = "Status do usuário atualizado com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao atualizar status do usuário.";
        }
        $update_stmt->close();
    }
    $stmt->close();
}

header("Location: painel.php");
exit;
?>