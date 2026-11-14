<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Editar Produto</h1>
            <nav>
                <a href="index.php">Início</a>
            </nav>
        </header>

        <main>
            <section class="card">
                <form id="form-editar">
                    <input type="hidden" id="id">
                    <div class="form-group">
                        <label for="nome">Nome do Produto:</label>
                        <input type="text" id="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoria:</label>
                        <input type="text" id="categoria" required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço (R$):</label>
                        <input type="number" step="0.01" id="preco" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Atualizar Produto</button>
                </form>
            </section>
        </main>
    </div>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const produtoId = urlParams.get('id');

        async function carregarProduto() {
            const res = await fetch(`api/produtos.php?id=${produtoId}`);
            const produto = await res.json();
            if (produto && produto.id) {
                document.getElementById('id').value = produto.id;
                document.getElementById('nome').value = produto.nome;
                document.getElementById('categoria').value = produto.categoria;
                document.getElementById('preco').value = produto.preco;
            } else {
                alert('Produto não encontrado!');
                window.location.href = 'index.php';
            }
        }

        document.addEventListener('DOMContentLoaded', carregarProduto);

        document.getElementById('form-editar').addEventListener('submit', async (e) => {
            e.preventDefault();
            const dadosAtualizados = {
                id: document.getElementById('id').value,
                nome: document.getElementById('nome').value,
                categoria: document.getElementById('categoria').value,
                preco: document.getElementById('preco').value
            };
            const response = await fetch('api/produtos.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dadosAtualizados)
            });
            if (response.ok) {
                // alert('Produto atualizado com sucesso!');
                window.location.href = 'index.php';
            }
        });
    </script>
</body>
</html>