<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Controle de Estoque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Controle de Estoque</h1>
            <nav>
                <a href="index.php" class="active">Início</a>
                <a href="cadastrar.php">Cadastrar Produto</a>
            </nav>
        </header>

        <main>
            <h5><a href="api/docs.html">Documentação API</a></h5>
            <section class="card">
                <h2>Lista de Produtos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Quantidade</th>
                            <th>Preço (R$)</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabela-produtos">
                        <!-- Preenchido via JavaScript -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    <script src="script.js"></script>
</body>
</html>