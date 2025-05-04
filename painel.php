<?php
session_start();
require_once 'config/conexao.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$is_admin = $_SESSION['is_admin'] == 1;
$result = $conexao->query("SELECT id, nome, email, senha, is_admin, status FROM users");
$users = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/img/coelho icon.png">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/painel.css">
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
                    <a href="logout.php">Log out</a>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <h1>Dados</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</p>
        <table>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Senha (hash)</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['nome']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars(substr($user['senha'], 0, 20)) . '...'; ?></td>
                <td><?php echo $user['status'] ? '1 (Ativo)' : '0 (Inativo)'; ?></td>
                <td>
                    <?php if ($is_admin): ?>
                        <a href="editar_usuario.php?id=<?php echo $user['id']; ?>" class="action-link edit">Editar</a> | 
                        <a href="excluir_usuario.php?id=<?php echo $user['id']; ?>" class="action-link drop" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                    <?php else: ?>
                        <span class="disabled-link">Editar</span> | 
                        <span class="disabled-link">Excluir</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p><a class="psswd-button" href="login.php">Sair</a></p>
    </main>
</body>
</html>