<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_nome']) || $_SESSION['usuario_tipo'] !== 'cliente') {
    echo "<script>alert('Acesso restrito à área do cliente.');
    window.location.href='abc.php?action=logar';</script>";
    exit();
}

$nome = $_SESSION['usuario_nome'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do cliente</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>../../public/view/css/style.css">
    <link id="color-theme" rel="stylesheet" href="../../public/css/input.css">
</head>
<body>
    <?php include 'layout/header.php'; ?>

    <main class="container">
        <div class="welcome-box">
            <h1>Olá, cliente(a) <?php echo htmlspecialchars($nome); ?>!</h1>
            <p>Seja bem-vindo(a) à sua área exclusiva de acesso.</p>
        </div>
    </main>

    <?php include 'layout/footer.php'; ?>
</body>
</html>

