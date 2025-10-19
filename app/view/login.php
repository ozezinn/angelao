<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>luumina.com/login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://db.onlinewebfonts.com/c/9b2f63108a5ca5afb6e3b268bca3dd9c?family=Yalta+Sans+W04+Light" rel="stylesheet">


    <link rel="stylesheet" href="../../public/css/input.css">
</head>

<body>

    <?php require_once __DIR__ . '/layout/header.php'; ?>

    <div id="alert-placeholder" class="container" style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: auto; max-width: 80%;"></div>

    <div class="form-container">
        <p class="title">Login</p>
        <form class="form" action="abc.php?action=autenticar" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="seu@email.com" required>
            </div>
            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha" placeholder="" required>
                <div class="forgot">
                    <a rel="noopener noreferrer" href="">Recuperar Senha?</a>
                </div>
            </div>
            <button class="sign">Login</button>
        </form>
        <p class="signup">Voce ainda não tem conta?
            <a rel="noopener noreferrer" href="cadastrar.php" class="">Cadastrar-se</a>
        </p>
    </div>

    <?php require_once __DIR__ . '/layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showAlert = (message, type = 'warning') => {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                // Limpa alertas antigos antes de mostrar um novo
                alertPlaceholder.innerHTML = ''; 
                
                const wrapper = document.createElement('div');
                let iconClass = 'bi-exclamation-triangle-fill'; // Ícone padrão
                if (type === 'success') {
                    iconClass = 'bi-check-circle-fill';
                }

                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible fade show d-flex align-items-center" role="alert">`,
                    `   <i class="bi ${iconClass} me-2"></i>`,
                    `   <div>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('');
                
                alertPlaceholder.append(wrapper);
                
                // Remove o alerta após 5 segundos
                setTimeout(() => {
                    wrapper.remove();
                }, 5000);
            };

            // Verifica os parâmetros da URL para decidir qual alerta mostrar
            const params = new URLSearchParams(window.location.search);
            const status = params.get('status');

            if (status === 'registered') {
                showAlert('Cadastro realizado com sucesso! Por favor, faça o login.', 'success');
            } else if (status === 'login_error') {
                showAlert('Email ou senha incorretos. Tente novamente.', 'danger');
            } else if (status === 'email_exists') {
                showAlert('Este email já está em uso. Tente outro ou faça login.', 'danger');
            }
        });
    </script>
</body>

</html>