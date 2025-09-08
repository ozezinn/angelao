
document.addEventListener("DOMContentLoaded", function() {
    const input = document.querySelector("input[name='email']");
    const tabela = document.getElementById("tabela-profissionais");

    input.addEventListener("focus", () => {
        tabela.style.display = "table"; // mostra a tabela
    });

    // Se quiser que apareça só quando começar a digitar:
    input.addEventListener("input", () => {
        if (input.value.trim() !== "") {
            tabela.style.display = "table";
        } else {
            tabela.style.display = "none";
        }
    });
});

