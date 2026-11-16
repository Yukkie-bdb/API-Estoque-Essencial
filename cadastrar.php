<?php
require_once 'api/conexao.php';

$mensagemErro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $quantidade = intval($_POST['quantidade'] ?? 0);
    $preco = floatval(str_replace(',', '.', $_POST['preco'] ?? 0));

    if (!empty($nome) && !empty($categoria)) {
        $instrucao = $pdo->prepare("INSERT INTO produtos (nome, categoria, quantidade, preco) VALUES (?, ?, ?, ?)");
        $instrucao->execute([$nome, $categoria, $quantidade, $preco]);
        header("Location: index.php");
        exit;
    } else {
        $mensagemErro = "Preencha todos os campos obrigatórios.";
    }
}
?>
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
                <?php if (!empty($mensagemErro)): ?>
                    <p style="color: red;"><?php echo $mensagemErro; ?></p>
                <?php endif; ?>
                <form action="cadastrar.php" method="POST">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="nome" style="display: block; margin-bottom: 5px;">Nome do Produto:</label>
                        <input type="text" id="nome" name="nome" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="categoria" style="display: block; margin-bottom: 5px;">Categoria:</label>
                        <input type="text" id="categoria" name="categoria" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="quantidade" style="display: block; margin-bottom: 5px;">Quantidade Inicial:</label>
                        <input type="number" id="quantidade" name="quantidade" min="0" value="0" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="preco" style="display: block; margin-bottom: 5px;">Preço (R$):</label>
                        <input type="number" step="0.01" id="preco" name="preco" min="0" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Salvar Produto</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
