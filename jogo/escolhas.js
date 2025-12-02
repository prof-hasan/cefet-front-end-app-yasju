const textoEl = document.querySelector('#texto');
const caixaOpcoes = document.querySelector('#caixa-opcoes');
let opcoesEl = Array.from(document.querySelectorAll('.opcao'));

let indice = 0;

let opcaoFocada = opcoesEl[indice];

// Foca na opção dada pelo parâmetro
function focaOpcao(opcao) {
    if(!opcao) return;

    opcaoFocada.classList.remove("marcada");
    opcaoFocada = opcao;
    opcaoFocada.classList.add("marcada");
}

// Seleciona a opção marcada
async function selecionaOpcao() {
    let opcaoSelecionada = opcoesEl[indice].textContent;

    let resposta = await fetch("index.php?opc=" + opcaoSelecionada);
    let resultado = await resposta.json();

    if(resultado.status === true)
        mudaConteudo(resultado.texto, resultado.opcoes);
}

// Muda o texto principal e as opcões
function mudaConteudo(texto, opcoes) {
    textoEl.textContent = texto;

    // Remove os antigos elementos
    opcoesEl.forEach(op => op.remove());
    opcoesEl.length = 0;

    // Cria os elementos e põe o texto neles
    opcoes.forEach(opcao => {
        const novoEl = document.createElement("div");
        novoEl.classList.add("opcao");
        novoEl.textContent = opcao;

        caixaOpcoes.appendChild(novoEl);
        opcoesEl.push(novoEl);
    });

    indice = 0;
    opcaoFocada = opcoesEl[indice];

    focaOpcao(opcaoFocada);
}

// Movimentação entre opções e seleção
document.addEventListener("keydown", async (e) => {
    if(e.key === 'Enter')
        await selecionaOpcao();

    if(e.key.toLowerCase() === 'd') {
        indice++;
        if(indice > opcoesEl.length - 1) indice = 0;
    }

    if(e.key.toLowerCase() === 'a') {
        indice--;
        if(indice < 0) indice = opcoesEl.length - 1;
    }

    focaOpcao(opcoesEl[indice]);
});