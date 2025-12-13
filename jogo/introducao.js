const botoesEl = document.querySelectorAll('#caixa-botoes button');

// Reseta o save e leva para o main.php
async function comecar() {
    await fetch("main.php?novoSave");
    window.location.href = "main.php";
}

// Leva para o main.php
function continuar() {
    window.location.href = "main.php";
}

// Verifica se os botões foram clicados
botoesEl.forEach((botao, indice) => {
    botao.addEventListener("click", async () => {
        // Primeiro botão
        if(indice === 0)
            await comecar();

        // Segundo botão
        if(indice === 1)
            continuar();
    });
});