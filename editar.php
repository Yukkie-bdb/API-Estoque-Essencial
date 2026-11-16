<?php
require_once 'api/conexao.php';

$identificador = intval($_GET['id'] ?? 0);
if ($identificador <= 0) {
    header("Location: index.php");
    exit;
}

$mensagemErro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $preco = floatval(str_replace(',', '.', $_POST['preco'] ?? 0));

    if (!empty($nome) && !empty($categoria)) {
        $instrucao = $pdo->prepare("UPDATE produtos SET nome = ?, categoria = ?, preco = ? WHERE id = ?");
        $instrucao->execute([$nome, $categoria, $preco, $identificador]);
        header("Location: index.php");
        exit;
    } else {
        $mensagemErro = "Preencha todos os campos obrigatórios.";
    }
}

$instrucao = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$instrucao->execute([$identificador]);
$produto = $instrucao->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header("Location: index.php");
    exit;
}
?>
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
                <?php if (!empty($mensagemErro)): ?>
                    <p style="color: red;"><?php echo $mensagemErro; ?></p>
                <?php endif; ?>
                <form action="editar.php?id=<?php echo $identificador; ?>" method="POST">
                    <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="nome" style="display: block; margin-bottom: 5px;">Nome do Produto:</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="categoria" style="display: block; margin-bottom: 5px;">Categoria:</label>
                        <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($produto['categoria']); ?>" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="preco" style="display: block; margin-bottom: 5px;">Preço (R$):</label>
                        <input type="number" step="0.01" id="preco" name="preco" value="<?php echo $produto['preco']; ?>" min="0" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Atualizar Produto</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
