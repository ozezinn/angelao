<div class="modal fade" id="managePortfolioModal" tabindex="-1" aria-labelledby="managePortfolioModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable"> <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="managePortfolioModalLabel">Gerenciar Portfólio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (!empty($portfolio_imagens)): ?>
                    <div class="row g-4"> <?php foreach ($portfolio_imagens as $imagem): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12"> 
                                <div class="card h-100 shadow-sm"> 
                                    <img src="../../<?= htmlspecialchars($imagem['caminho_arquivo']) ?>"
                                        class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($imagem['titulo']) ?>">
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title text-truncate" title="<?= htmlspecialchars($imagem['titulo']) ?>">
                                            <?= htmlspecialchars($imagem['titulo']) ?>
                                         </h6>
                                        <div class="mt-auto d-flex justify-content-between pt-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-portfolio-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addPortfolioModal" 
                                                data-item-id="<?= $imagem['id_item'] ?>"
                                                data-item-title="<?= htmlspecialchars($imagem['titulo']) ?>"
                                                data-item-description="<?= htmlspecialchars($imagem['descricao'] ?? '') ?>"
                                                data-item-service-id="<?= htmlspecialchars($imagem['id_servico'] ?? '') ?>">
                                                <i class="bi bi-pencil-square"></i> Editar
                                            </button>
                                            
                                            <a href="#"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#confirmDeleteModal"
                                                data-href="abc.php?action=deleteFoto&id=<?= $imagem['id_item'] ?>"
                                                data-message="Tem certeza que deseja excluir esta foto?">
                                                <i class="bi bi-trash-fill"></i> Excluir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted mt-3">Você ainda não adicionou nenhuma foto ao seu portfólio.</p>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>