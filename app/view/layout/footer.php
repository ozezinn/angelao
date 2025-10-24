</main>

<footer class="footer-custom text-center p-4">
    <p class="mb-0">© 2025 luumina. todos direitos reservados.</p>
    <div class="footer-links">
        <a href="termos.php">Termos de Uso</a>
        <span>|</span>
        <a href="politica.php">Política de Privacidade</a>
    </div>
</footer>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmDeleteModalMessage">Tem certeza que deseja excluir este item? Esta ação é irreversível.</p>
            </div>
            <div class="modal-footer" style="border-top: none;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" class="btn btn-danger" id="confirmDeleteModalButton">Excluir</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModal) {
        const confirmDeleteModalButton = document.getElementById('confirmDeleteModalButton');
        const confirmDeleteModalMessage = document.getElementById('confirmDeleteModalMessage');

        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            // Botão ou link que acionou o modal
            const triggerElement = event.relatedTarget;
            
            // Pega o URL de exclusão do atributo 'data-href'
            const deleteUrl = triggerElement.getAttribute('data-href');
            
            // Pega a mensagem customizada (se houver)
            const message = triggerElement.getAttribute('data-message');

            // Atualiza a mensagem do modal
            if (message) {
                confirmDeleteModalMessage.textContent = message;
            } else {
                // Mensagem padrão
                confirmDeleteModalMessage.textContent = "Tem certeza que deseja excluir este item? Esta ação é irreversível.";
            }

            // Atualiza o link do botão "Excluir"
            if (confirmDeleteModalButton) {
                confirmDeleteModalButton.href = deleteUrl;
            }
        });
    }
});
</script>

</body>
</html>