<?php
session_start();
require_once 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $conexao->prepare("SELECT id, nome, senha, is_admin, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['status'] == 0) {
            $erro = "Conta inativa. Contate o administrador.";
        } elseif (password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header("Location: painel.php");
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Email não encontrado.";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/img/coelho icon.png" >
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/psswd.css">
    <title>Segurança em Sistemas para Internet</title>
</head>
<body>
    <nav>
        <a href="home.html">
            <div class="logo">
                <img src="assets/img/coelho icon.png" alt="imagem de um coelho fofo digitando" height="100px">
            </div>
        </a>
        <div>
            <div class="menu-container">
                <input type="checkbox" id="menu-toggle" class="menu-toggle">
                <label for="menu-toggle">
                    <img src="assets/img/menu.png" alt="Menu" class="menu-button" height="60px">
                </label>
                <div class="menu-dropdown">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php">Log out</a>
                    <?php else: ?>
                        <a href="login.php">Log In</a>
                    <?php endif; ?>
                    <a href="cadastro.php">Cadastro</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="psswd-wrapper">
        <div class="psswd-container">
            <h1 class="psswd-title">Login</h1>
            <?php 
            if (isset($_SESSION['mensagem'])) {
                echo "<p class='sucesso'>{$_SESSION['mensagem']}</p>";
                unset($_SESSION['mensagem']);
            }
            if (isset($erro)) echo "<p class='erro'>$erro</p>"; 
            ?>
            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit" class="psswd-button">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>