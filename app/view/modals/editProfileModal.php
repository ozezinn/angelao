<?php
// Array com todas as especialidades disponíveis no site
$todas_especialidades = [
    "Aniversários", "Arquitetura", "Boudoir", "Casamentos", "Chás de Bebê",
    "Drone", "Ensaios", "Esportes", "Eventos Corporativos", "Eventos Religiosos",
    "Formaturas", "Gastronomia", "Institucional", "Moda", "Pet",
    "Produtos", "Shows", "Viagem"
];

// Lembrete: A página que inclui este modal precisa definir as variáveis abaixo
// com os dados do banco, para que os campos já venham preenchidos.
// Exemplo:
// $nome = $dados_do_usuario['nome'];
// $foto_perfil = $dados_do_usuario['foto_perfil'];
// $biografia = $dados_do_usuario['biografia'];
// $estado_usuario = $dados_do_usuario['estado_uf']; // Ex: "SP"
// $cidade_usuario = $dados_do_usuario['cidade'];   // Ex: "São Paulo"
// $especialidades = buscarEspecialidadesDoUsuario($usuario_id); // Retorna um array. Ex: ['Casamentos', 'Ensaios']
?>

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
                            <img src="../<?= htmlspecialchars($foto_perfil ?? 'caminho/para/imagem/padrao.jpg') ?>" class="img-fluid rounded-circle mb-3" alt="Foto atual">
                            <label for="foto_perfil" class="form-label">Alterar foto de perfil</label>
                            <input class="form-control form-control-sm" type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($nome ?? '') ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="estado" class="form-label">Estado (UF)</label>
                                    <select class="form-select" name="estado" id="estado-modal">
                                        <option value="">Selecione um estado</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <select class="form-select" name="cidade" id="cidade-modal" disabled>
                                        <option value="">Aguardando estado...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="biografia" class="form-label">Biografia</label>
                                <textarea class="form-control" id="biografia" name="biografia" rows="4"><?= htmlspecialchars($biografia ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Minhas Especialidades</label>
                        <div class="row">
                            <?php foreach ($todas_especialidades as $esp) : ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="especialidades[]" value="<?= htmlspecialchars($esp) ?>" id="esp-modal-<?= str_replace(' ', '', $esp) ?>" 
                                            <?= (isset($especialidades) && in_array($esp, $especialidades)) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="esp-modal-<?= str_replace(' ', '', $esp) ?>"><?= htmlspecialchars($esp) ?></label>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // É importante usar seletores de ID únicos para o modal para evitar conflitos
    const estadoSelect = document.getElementById('estado-modal');
    const cidadeSelect = document.getElementById('cidade-modal');

    // Pega os valores salvos no banco que foram passados pelo PHP
    const estadoSalvo = "<?= $estado_usuario ?? '' ?>"; // Ex: 'SP'
    const cidadeSalva = "<?= $cidade_usuario ?? '' ?>"; // Ex: 'São Paulo'

    // Carrega todos os estados do Brasil da API do IBGE
    fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
        .then(res => res.json())
        .then(estados => {
            estados.forEach(estado => {
                const isSelected = estado.sigla === estadoSalvo ? 'selected' : '';
                estadoSelect.innerHTML += `<option value="${estado.sigla}" ${isSelected}>${estado.nome}</option>`;
            });
            // Se um estado já estava salvo no perfil, dispara o evento 'change'
            // para carregar as cidades correspondentes automaticamente.
            if (estadoSalvo) {
                estadoSelect.dispatchEvent(new Event('change'));
            }
        });

    // Evento que dispara a busca por cidades toda vez que um estado é selecionado
    estadoSelect.addEventListener('change', function () {
        const uf = this.value;
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
        cidadeSelect.disabled = true;

        if (!uf) {
            cidadeSelect.innerHTML = '<option value="">Aguardando estado...</option>';
            return;
        }

        fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`)
            .then(res => res.json())
            .then(cidades => {
                cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
                cidades.forEach(cidade => {
                    // Verifica se a cidade da API é a que está salva no perfil
                    const isSelected = cidade.nome === cidadeSalva ? 'selected' : '';
                    cidadeSelect.innerHTML += `<option value="${cidade.nome}" ${isSelected}>${cidade.nome}</option>`;
                });
                cidadeSelect.disabled = false;
            });
    });
});
</script>