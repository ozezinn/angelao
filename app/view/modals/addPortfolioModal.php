<div class="modal fade" id="addPortfolioModal" tabindex="-1" aria-labelledby="addPortfolioModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="abc.php?action=uploadFotoPortfolio" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPortfolioModalLabel">Adicionar Nova Foto ao Portfólio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tituloFoto" class="form-label">Título da Foto</label>
                        <input type="text" class="form-control" id="tituloFoto" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricaoFoto" class="form-label">Descrição (opcional)</label>
                        <textarea class="form-control" id="descricaoFoto" name="descricao" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="servicoRelacionado" class="form-label">Especialidade Relacionada</label>
                        <select class="form-select" id="servicoRelacionado" name="id_servico" required>
                            <?php
                            // As variáveis $todos_servicos (com ID/Nome) e $especialidades (só Nomes do perfil)
                            // são carregadas pelo 'areaProfissional.php'.
                            
                            // Verifica se o profissional tem especialidades cadastradas
                            if (isset($especialidades) && !empty($especialidades)) {

                                echo '<option selected disabled value="">Selecione uma de suas especialidades</option>';

                                // Garante que a lista de todos os serviços também foi carregada
                                if (isset($todos_servicos)) {
                                    // Itera sobre TODOS os serviços do banco
                                    foreach ($todos_servicos as $servico) {

                                        // Exibe a opção APENAS SE o nome do serviço (ex: "Casamentos")
                                        // estiver no array de especialidades do profissional (ex: ["Casamentos", "Ensaios"])
                                        if (in_array($servico['nome_servico'], $especialidades)) {
                                            echo '<option value="' . htmlspecialchars($servico['id_servico']) . '">'
                                                . htmlspecialchars($servico['nome_servico'])
                                                . '</option>';
                                        }
                                    }
                                }

                            } else {
                                // Mensagem de fallback se o profissional não tiver cadastrado nenhuma especialidade no perfil
                                echo '<option selected disabled value="">Vá em "Editar Perfil" e cadastre suas especialidades primeiro</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="arquivoFoto" class="form-label">Selecione a Imagem</label>
                        <input class="form-control" type="file" id="arquivoFoto" name="arquivo_foto" accept="image/*"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>