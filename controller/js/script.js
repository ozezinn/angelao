/**
 * Troca o tema de cores do site alterando o arquivo CSS.
 * @param {string} themeName - O nome do tema a ser aplicado (ex: 'deuteranopia').
 */
function switchTheme(themeName) {
    // Encontra a tag <link> que carrega o tema pelo seu ID.
    const themeStylesheet = document.getElementById('color-theme');

    // Monta o caminho para o novo arquivo CSS.
    const newThemePath = `css/${themeName}.css`;

    // Altera o atributo 'href' para carregar o novo tema.
    if (themeStylesheet) {
        themeStylesheet.setAttribute('href', newThemePath);
    } else {
        console.error('Elemento de tema com ID "color-theme" não encontrado.');
    }

    // Opcional: Salvar a preferência do usuário no localStorage
    // Isso faz com que a escolha do tema persista ao recarregar a página.
    localStorage.setItem('selectedTheme', themeName);
}

/**
 * Verifica se há um tema salvo no localStorage e o aplica ao carregar a página.
 */
function loadSavedTheme() {
    const savedTheme = localStorage.getItem('selectedTheme');
    if (savedTheme) {
        switchTheme(savedTheme);
    }
}

// Executa a função para carregar o tema salvo assim que o documento for carregado.
document.addEventListener('DOMContentLoaded', loadSavedTheme);