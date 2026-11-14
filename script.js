document.addEventListener('DOMContentLoaded', () => {
    carregarProdutos();    
});

async function carregarProdutos() {
    const tbody = document.getElementById('tabela-produtos');
    if (!tbody) return;

    try {
        const res = await fetch('api/produtos.php');
        const produtos = await res.json();

        tbody.innerHTML = '';
        produtos.forEach(p => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${p.id}</td>
                <td>${p.nome}</td>
                <td>${p.categoria}</td>
                <td>${p.quantidade}</td>
                <td>R$ ${Number(p.preco).toFixed(2)}</td>
                <td>
                    <button onclick="movimentar(${p.id}, 'entrada')" class="btn btn-sm btn-success">+ Entrada</button>
                    <button onclick="movimentar(${p.id}, 'saida')" class="btn btn-sm btn-warning">- Saída</button>
                    <a href="editar.php?id=${p.id}" class="btn btn-sm btn-info">Editar</a>
                    <button onclick="excluir(${p.id})" class="btn btn-sm btn-danger">Excluir</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Erro ao carregar produtos:', error);
    }
}

async function movimentar(id, tipo) {
    const qtdStr = prompt(`Informe a quantidade para ${tipo}:`);
    const qtd = parseInt(qtdStr);
    if (!isNaN(qtd) && qtd > 0) {
        const res = await fetch('api/produtos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ acao: 'movimentar', id, tipo, quantidade: qtd })
        });
        if (res.ok) {
            carregarProdutos();
        }
    } else if (qtdStr !== null) {
        alert('Quantidade inválida!');
    }
}

async function excluir(id) {
    if (confirm('Deseja realmente excluir este produto?')) {
        const res = await fetch(`api/produtos.php?id=${id}`, {
            method: 'DELETE'
        });
        if (res.ok) {
            carregarProdutos();
        }
    }
}