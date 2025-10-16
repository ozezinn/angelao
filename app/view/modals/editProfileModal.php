<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="abc.php?action=updateProfile" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Editar Perfil Profissional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="../<?= htmlspecialchars($foto_perfil) ?>" class="img-fluid rounded-circle mb-3" alt="Foto atual">
                            <label for="foto_perfil" class="form-label">Alterar foto de perfil</label>
                            <input class="form-control form-control-sm" type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="localizacao" class="form-label">Localização</label>
                                <input type="text" class="form-control" id="localizacao" name="localizacao" value="<?= htmlspecialchars($localizacao) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="biografia" class="form-label">Biografia</label>
                                <textarea class="form-control" id="biografia" name="biografia" rows="4"><?= htmlspecialchars($biografia) ?></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Especialidades</label>
                        <div class="row">
                            <?php foreach ($todas_especialidades as $esp) : ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="especialidades[]" value="<?= $esp ?>" id="esp-<?= str_replace(' ', '', $esp) ?>" 
                                            <?= in_array($esp, $especialidades) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="esp-<?= str_replace(' ', '', $esp) ?>"><?= $esp ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>