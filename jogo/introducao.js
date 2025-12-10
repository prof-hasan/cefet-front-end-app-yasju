const botoesEl = document.querySelectorAll('#caixa-botoes button');

async function comecar() {
    await fetch("main.php?novoSave");
    window.location.href = "main.php";
}

function continuar() {
    window.location.href = "main.php";
}

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