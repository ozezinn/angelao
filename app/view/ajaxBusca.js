document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    const performSearch = async (term) => {
        // 1. REMOVEMOS a verificação de term.length < 2
        // Agora o backend decide o que fazer com buscas vazias.

        try {
            const response = await fetch(`abc.php?action=searchProfissionais&term=${encodeURIComponent(term)}`);
            const profissionais = await response.json();

            searchResults.innerHTML = ''; // Limpa resultados antigos

            if (profissionais.length > 0) {
                profissionais.forEach(prof => {
                    const item = document.createElement('a');
                    item.href = `abc.php?action=verPerfil&id=${prof.id_usuario}`;
                    item.className = 'search-result-item';
                    
                    const foto = prof.foto_perfil ? `../${prof.foto_perfil}` : '../view/img/profile-placeholder.jpg';
                    const local = prof.localizacao ? prof.localizacao : 'Local não informado';

                    item.innerHTML = `
                        <img src="${foto}" class="search-result-img" alt="${prof.nome}">
                        <div>
                            <div class="search-result-name">${prof.nome}</div>
                            <div class="search-result-location">${local}</div>
                        </div>
                    `;
                    searchResults.appendChild(item);
                });
                searchResults.style.display = 'block';
            } else {
                // Se a busca não vazia não retornar nada, mostra a mensagem.
                if(term.length > 0){
                    searchResults.innerHTML = '<div class="search-no-results">Nenhum profissional encontrado.</div>';
                    searchResults.style.display = 'block';
                } else {
                    searchResults.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Erro na busca:', error);
            searchResults.style.display = 'none';
        }
    };

    // Evento que dispara a busca enquanto o usuário digita
    searchInput.addEventListener('keyup', (e) => {
        performSearch(e.target.value);
    });

    // ==============================================================
    // 2. ADICIONAMOS um novo 'event listener' para o evento 'focus'
    // ==============================================================
    searchInput.addEventListener('focus', (e) => {
        // Chama a função de busca com o valor atual (que geralmente estará vazio no primeiro clique)
        performSearch(e.target.value);
    });

    // Esconde os resultados se o usuário clicar fora da caixa de busca
    document.addEventListener('click', function(e) {
        if (!searchResults.contains(e.target) && !searchInput.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});