<?php
require_once 'api/conexao.php';

if (isset($_GET['deletar'])) {
    $identificador = intval($_GET['deletar']);
    $instrucao = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $instrucao->execute([$identificador]);
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'movimentar') {
    $identificador = intval($_POST['id']);
    $tipo = $_POST['tipo'];
    $quantidade = intval($_POST['quantidade']);

    $instrucao = $pdo->prepare("SELECT quantidade FROM produtos WHERE id = ?");
    $instrucao->execute([$identificador]);
    $produto = $instrucao->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        $novaQuantidade = $produto['quantidade'];
        if ($tipo === 'entrada') {
            $novaQuantidade += $quantidade;
        } elseif ($tipo === 'saida') {
            $novaQuantidade = max(0, $novaQuantidade - $quantidade);
        }
        $atualizacao = $pdo->prepare("UPDATE produtos SET quantidade = ? WHERE id = ?");
        $atualizacao->execute([$novaQuantidade, $identificador]);
    }
    header("Location: index.php");
    exit;
}

$instrucao = $pdo->query("SELECT * FROM produtos");
$produtos = $instrucao->fetchAll(PDO::FETCH_ASSOC);
?>
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
                    <tbody>
                        <?php if (empty($produtos)): ?>
                            <tr><td colspan="6" style="text-align: center;">Nenhum produto cadastrado.</td></tr>
                        <?php else: ?>
                            <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td><?php echo $produto['id']; ?></td>
                                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                                    <td><?php echo $produto['quantidade']; ?></td>
                                    <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                                    <td>
                                        <form action="index.php" method="POST" style="display:inline-block; margin-right:2px;">
                                            <input type="hidden" name="acao" value="movimentar">
                                            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                            <input type="hidden" name="tipo" value="entrada">
                                            <input type="number" name="quantidade" value="1" min="1" style="width: 45px;" required>
                                            <button type="submit" class="btn btn-sm btn-success">+ Entr.</button>
                                        </form>
                                        <form action="index.php" method="POST" style="display:inline-block; margin-right:2px;">
                                            <input type="hidden" name="acao" value="movimentar">
                                            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
                                            <input type="hidden" name="tipo" value="saida">
                                            <input type="number" name="quantidade" value="1" min="1" style="width: 45px;" required>
                                            <button type="submit" class="btn btn-sm btn-warning">- Saída</button>
                                        </form>
                                        <a href="editar.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-info" style="text-decoration:none; padding:4px 8px; background:#17a2b8; color:white; border-radius:4px; font-size:12px;">Editar</a>
                                        <a href="index.php?deletar=<?php echo $produto['id']; ?>" class="btn btn-sm btn-danger" style="text-decoration:none; padding:4px 8px; background:#dc3545; color:white; border-radius:4px; font-size:12px;">Excluir</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>
</html>
