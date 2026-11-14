<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Cadastrar Novo Produto</h1>
            <nav>
                <a href="index.php">Início</a>
                <a href="cadastrar.php" class="active">Cadastrar Produto</a>
            </nav>
        </header>

        <main>
            <section class="card">
                <form id="form-cadastrar">
                    <div class="form-group">
                        <label for="nome">Nome do Produto:</label>
                        <input type="text" id="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoria:</label>
                        <input type="text" id="categoria" required>
                    </div>
                    <div class="form-group">
                        <label for="quantidade">Quantidade Inicial:</label>
                        <input type="number" id="quantidade" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço (R$):</label>
                        <input type="number" step="0.01" id="preco" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Produto</button>
                </form>
            </section>
        </main>
    </div>
    <script>
        document.getElementById('form-cadastrar').addEventListener('submit', async (e) => {
            e.preventDefault();
            const produto = {
                nome: document.getElementById('nome').value,
                categoria: document.getElementById('categoria').value,
                quantidade: document.getElementById('quantidade').value,
                preco: document.getElementById('preco').value
            };
            const response = await fetch('api/produtos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(produto)
            });
            if (response.ok) {
                // alert('Produto cadastrado com sucesso!');
                window.location.href = 'index.php';
            }
        });
    </script>
</body>
</html>