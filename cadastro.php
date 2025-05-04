<?php
session_start();
require_once 'config/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $admin_email = 'admin@admin.com';
    $is_admin = ($email === $admin_email) ? 1 : 0;

    $stmt = $conexao->prepare("INSERT INTO users (nome, email, senha, is_admin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nome, $email, $senha, $is_admin);

    try {
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Cadastro realizado com sucesso!";
            header("Location: login.php");
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $erro = "Erro: Email já cadastrado.";
        } else {
            $erro = "Erro ao realizar cadastro: " . $e->getMessage();
        }
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
        <a href="home.php">
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
            <h1 class="psswd-title">Cadastrar</h1>
            <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>
            <form method="POST">
                <input type="hidden" name="action" value="cadastro">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required>
                </div>
                <button type="submit" class="psswd-button">Cadastrar</button>
            </form>
            <p><a class="texto"  href="login.php">Já tem conta? Faça login</a></p>
        </div>
    </div>
</body>
</html>