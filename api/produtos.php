<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'conexao.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM produtos");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        
        if (isset($data['acao']) && $data['acao'] === 'movimentar') {
            $id = $data['id'];
            $tipo = $data['tipo'];
            $qtd = intval($data['quantidade']);
            
            $stmt = $pdo->prepare("SELECT quantidade FROM produtos WHERE id = ?");
            $stmt->execute([$id]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($prod) {
                $novaQtd = $prod['quantidade'];
                if ($tipo === 'entrada') {
                    $novaQtd += $qtd;
                } elseif ($tipo === 'saida') {
                    $novaQtd = max(0, $novaQtd - $qtd);
                }
                $up = $pdo->prepare("UPDATE produtos SET quantidade = ? WHERE id = ?");
                $up->execute([$novaQtd, $id]);
                echo json_encode(['sucesso' => true, 'nova_quantidade' => $novaQtd]);
            } else {
                echo json_encode(['erro' => 'Produto não encontrado']);
            }
            break;
        }

        $stmt = $pdo->prepare("INSERT INTO produtos (nome, categoria, quantidade, preco) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['nome'] ?? '',
            $data['categoria'] ?? '',
            intval($data['quantidade'] ?? 0),
            floatval($data['preco'] ?? 0)
        ]);
        echo json_encode(['sucesso' => true, 'id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        
        if ($id) {
            $verifica = $pdo->prepare("SELECT id FROM produtos WHERE id = ?");
            $verifica->execute([$id]);
            
            if ($verifica->fetch()) {
                $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, categoria = ?, preco = ? WHERE id = ?");
                $stmt->execute([
                    $data['nome'],
                    $data['categoria'],
                    floatval($data['preco']),
                    $id
                ]);
                echo json_encode(['sucesso' => true]);
            } else {
                http_response_code(404);
                echo json_encode(['erro' => 'Produto não encontrado para atualização']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['erro' => 'ID não informado']);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $verifica = $pdo->prepare("SELECT id FROM produtos WHERE id = ?");
            $verifica->execute([$id]);
            
            if ($verifica->fetch()) {
                $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
                $stmt->execute([$id]);
                echo json_encode(['sucesso' => true]);
            } else {
                http_response_code(404);
                echo json_encode(['erro' => 'Produto não encontrado para exclusão']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['erro' => 'ID não informado']);
        }
        break;
}