<?php
// Esta verificação garante que o nome do usuário esteja disponível
// mesmo que este arquivo seja incluído em diferentes contextos.
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Usuário'; 
?>

<div class="modal fade" id="userActionsModal" tabindex="-1" aria-labelledby="userActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userActionsModalLabel">Minha Conta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4">Olá, <strong><?= htmlspecialchars($nomeUsuario) ?></strong>! O que você gostaria de fazer?</p>
                
                <div class="list-group">
                    
                    <a href="abc.php?action=minhaCaixaDeEntrada" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-inbox-fill me-3"></i>
                        Caixa de Entrada
                    </a>
                    <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'profissional'): ?>
                    <button type="button" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="bi bi-pencil-square me-3"></i>
                        Editar Perfil do Fotógrafo
                    </button>
                    <?php endif; ?>

                    <a href="abc.php?action=alterarSenha" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-key-fill me-3"></i>
                        Alterar Senha
                    </a>

                    <a href="abc.php?action=logout" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-box-arrow-right me-3"></i>
                        Sair (Logout)
                    </a>

                    <a href="#" class="list-group-item list-group-item-action list-group-item-danger d-flex align-items-center mt-3" 
                        data-bs-toggle="modal" 
                        data-bs-target="#confirmDeleteModal" 
                        data-href="abc.php?action=excluirConta" 
                        data-message="Tem certeza que deseja excluir sua conta? Esta ação é irreversível.">
                        <i class="bi bi-exclamation-triangle-fill me-3"></i>
                        Excluir Minha Conta
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>