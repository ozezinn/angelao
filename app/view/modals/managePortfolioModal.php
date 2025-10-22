<div class="modal fade" id="managePortfolioModal" tabindex="-1" aria-labelledby="managePortfolioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="managePortfolioModalLabel">Gerenciar Portfólio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($portfolio_imagens)) : ?>
                    <div class="row g-3">
                        <?php foreach ($portfolio_imagens as $imagem) : ?>
                            <div class="col-md-3 col-6">
                                <div class="portfolio-thumb-wrapper">
                                    <img src="../../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($imagem['titulo']) ?>">
                                    <div class="portfolio-thumb-overlay">
                                        <p class="portfolio-thumb-title"><?= htmlspecialchars($imagem['titulo']) ?></p>
                                        <a href="abc.php?action=deleteFoto&id=<?= $imagem['id_item'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta foto?');">
                                            <i class="bi bi-trash-fill"></i> Excluir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center">Você ainda não adicionou nenhuma foto ao seu portfólio.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>