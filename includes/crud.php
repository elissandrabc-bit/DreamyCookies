<?php
/**
 * CRUD completo — clientes, cookies e categorias (admin).
 */

require_once __DIR__ . '/functions.php';

function buscarClientePorId(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, nome, email, telefone, data_cadastro FROM clientes WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row) {
        return null;
    }

    return [
        'id'            => (int) $row['id'],
        'nome'          => $row['nome'],
        'email'         => $row['email'],
        'telefone'      => $row['telefone'],
        'data_cadastro' => $row['data_cadastro'],
    ];
}

function criarClienteAdmin(PDO $pdo, array $dados): array
{
    $dados['confirmar_senha'] = $dados['confirmar_senha'] ?? $dados['senha'] ?? '';
    return cadastrarCliente($pdo, $dados);
}

function atualizarCliente(PDO $pdo, int $id, array $dados): array
{
    $cliente = buscarClientePorId($pdo, $id);

    if ($cliente === null) {
        return ['sucesso' => false, 'mensagem' => 'Cliente não encontrado.'];
    }

    $nome = trim($dados['nome'] ?? '');
    $email = trim($dados['email'] ?? '');
    $telefone = trim($dados['telefone'] ?? '');
    $senha = $dados['senha'] ?? '';

    if ($nome === '' || $email === '' || $telefone === '') {
        return ['sucesso' => false, 'mensagem' => 'Preencha nome, e-mail e telefone.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['sucesso' => false, 'mensagem' => 'E-mail inválido.'];
    }

    $stmt = $pdo->prepare('SELECT id FROM clientes WHERE email = ? AND id <> ? LIMIT 1');
    $stmt->execute([$email, $id]);

    if ($stmt->fetch()) {
        return ['sucesso' => false, 'mensagem' => 'Este e-mail já pertence a outro cliente.'];
    }

    if ($senha !== '') {
        if (strlen($senha) < 6) {
            return ['sucesso' => false, 'mensagem' => 'A nova senha deve ter no mínimo 6 caracteres.'];
        }

        $sql = 'UPDATE clientes SET nome = ?, email = ?, telefone = ?, senha_hash = ? WHERE id = ?';
        $params = [$nome, $email, $telefone, password_hash($senha, PASSWORD_DEFAULT), $id];
    } else {
        $sql = 'UPDATE clientes SET nome = ?, email = ?, telefone = ? WHERE id = ?';
        $params = [$nome, $email, $telefone, $id];
    }

    $pdo->prepare($sql)->execute($params);

    return ['sucesso' => true, 'mensagem' => 'Cliente atualizado com sucesso.'];
}

function excluirCliente(PDO $pdo, int $id): array
{
    $cliente = buscarClientePorId($pdo, $id);

    if ($cliente === null) {
        return ['sucesso' => false, 'mensagem' => 'Cliente não encontrado.'];
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM pedidos WHERE cliente_id = ?');
    $stmt->execute([$id]);
    $qtdPedidos = (int) $stmt->fetchColumn();

    $pdo->prepare('DELETE FROM clientes WHERE id = ?')->execute([$id]);

    if ($qtdPedidos > 0) {
        return [
            'sucesso'  => true,
            'mensagem' => 'Cliente excluído. ' . $qtdPedidos . ' pedido(s) permanecem no sistema (sem vínculo de cliente).',
        ];
    }

    return ['sucesso' => true, 'mensagem' => 'Cliente excluído com sucesso.'];
}

function buscarCookiesAdmin(PDO $pdo): array
{
    $sql = "
        SELECT
            c.id,
            c.nome,
            c.descricao,
            c.preco,
            c.imagem,
            c.ativo,
            GROUP_CONCAT(DISTINCT cat.nome ORDER BY cat.nome SEPARATOR ', ') AS categorias
        FROM cookies c
        LEFT JOIN cookie_categoria cc ON cc.cookie_id = c.id
        LEFT JOIN categorias cat ON cat.id = cc.categoria_id
        GROUP BY c.id, c.nome, c.descricao, c.preco, c.imagem, c.ativo
        ORDER BY c.nome ASC
    ";

    $stmt = $pdo->query($sql);
    $cookies = [];

    while ($row = $stmt->fetch()) {
        $cookies[] = [
            'id'         => (int) $row['id'],
            'nome'       => $row['nome'],
            'descricao'  => $row['descricao'],
            'preco'      => (float) $row['preco'],
            'imagem'     => $row['imagem'],
            'ativo'      => (int) $row['ativo'],
            'categorias' => $row['categorias'] ?? '',
        ];
    }

    return $cookies;
}

function buscarCookieAdminPorId(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, nome, descricao, preco, imagem, ativo FROM cookies WHERE id = ? LIMIT 1'
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row) {
        return null;
    }

    $stmtCat = $pdo->prepare('SELECT categoria_id FROM cookie_categoria WHERE cookie_id = ?');
    $stmtCat->execute([$id]);
    $categoriasIds = array_map('intval', $stmtCat->fetchAll(PDO::FETCH_COLUMN));

    return [
        'id'             => (int) $row['id'],
        'nome'           => $row['nome'],
        'descricao'      => $row['descricao'],
        'preco'          => (float) $row['preco'],
        'imagem'         => $row['imagem'],
        'ativo'          => (int) $row['ativo'],
        'categorias_ids' => $categoriasIds,
    ];
}

function validarDadosCookie(array $dados): array
{
    $erros = [];
    $nome = trim($dados['nome'] ?? '');
    $descricao = trim($dados['descricao'] ?? '');
    $preco = isset($dados['preco']) ? (float) str_replace(',', '.', (string) $dados['preco']) : -1;
    $imagem = trim($dados['imagem'] ?? '');

    if ($nome === '') {
        $erros[] = 'Informe o nome do cookie.';
    }

    if ($descricao === '') {
        $erros[] = 'Informe a descrição.';
    }

    if ($preco < 0) {
        $erros[] = 'Preço inválido.';
    }

    if ($imagem === '') {
        $erros[] = 'Informe o nome do arquivo de imagem.';
    }

    return ['valido' => empty($erros), 'erros' => $erros, 'preco' => $preco];
}

function sincronizarCategoriasCookie(PDO $pdo, int $cookieId, array $categoriasIds): void
{
    $pdo->prepare('DELETE FROM cookie_categoria WHERE cookie_id = ?')->execute([$cookieId]);

    $stmt = $pdo->prepare('INSERT INTO cookie_categoria (cookie_id, categoria_id) VALUES (?, ?)');

    foreach ($categoriasIds as $catId) {
        $catId = (int) $catId;
        if ($catId > 0) {
            $stmt->execute([$cookieId, $catId]);
        }
    }
}

function criarCookie(PDO $pdo, array $dados, array $categoriasIds): array
{
    $validacao = validarDadosCookie($dados);

    if (!$validacao['valido']) {
        return ['sucesso' => false, 'mensagem' => implode(' ', $validacao['erros'])];
    }

    $ativo = !empty($dados['ativo']) ? 1 : 0;

    $stmt = $pdo->prepare(
        'INSERT INTO cookies (nome, descricao, preco, imagem, ativo) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        trim($dados['nome']),
        trim($dados['descricao']),
        $validacao['preco'],
        trim($dados['imagem']),
        $ativo,
    ]);

    $cookieId = (int) $pdo->lastInsertId();
    sincronizarCategoriasCookie($pdo, $cookieId, $categoriasIds);

    return ['sucesso' => true, 'mensagem' => 'Cookie cadastrado com sucesso.'];
}

function atualizarCookie(PDO $pdo, int $id, array $dados, array $categoriasIds): array
{
    if (buscarCookieAdminPorId($pdo, $id) === null) {
        return ['sucesso' => false, 'mensagem' => 'Cookie não encontrado.'];
    }

    $validacao = validarDadosCookie($dados);

    if (!$validacao['valido']) {
        return ['sucesso' => false, 'mensagem' => implode(' ', $validacao['erros'])];
    }

    $ativo = !empty($dados['ativo']) ? 1 : 0;

    $stmt = $pdo->prepare(
        'UPDATE cookies SET nome = ?, descricao = ?, preco = ?, imagem = ?, ativo = ? WHERE id = ?'
    );
    $stmt->execute([
        trim($dados['nome']),
        trim($dados['descricao']),
        $validacao['preco'],
        trim($dados['imagem']),
        $ativo,
        $id,
    ]);

    sincronizarCategoriasCookie($pdo, $id, $categoriasIds);

    return ['sucesso' => true, 'mensagem' => 'Cookie atualizado com sucesso.'];
}

function excluirCookie(PDO $pdo, int $id): array
{
    if (buscarCookieAdminPorId($pdo, $id) === null) {
        return ['sucesso' => false, 'mensagem' => 'Cookie não encontrado.'];
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM pedido_itens WHERE cookie_id = ?');
    $stmt->execute([$id]);
    $qtd = (int) $stmt->fetchColumn();

    if ($qtd > 0) {
        return [
            'sucesso'  => false,
            'mensagem' => 'Não foi possível excluir: este cookie está em ' . $qtd . ' item(ns) de pedido(s). Desative-o em vez de excluir.',
        ];
    }

    $pdo->prepare('DELETE FROM cookies WHERE id = ?')->execute([$id]);

    return ['sucesso' => true, 'mensagem' => 'Cookie excluído com sucesso.'];
}

function buscarCategoriaPorId(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT id, nome, descricao FROM categorias WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row) {
        return null;
    }

    return [
        'id'        => (int) $row['id'],
        'nome'      => $row['nome'],
        'descricao' => $row['descricao'],
    ];
}

function validarDadosCategoria(array $dados): array
{
    $erros = [];
    $nome = trim($dados['nome'] ?? '');

    if ($nome === '') {
        $erros[] = 'Informe o nome da categoria.';
    }

    return ['valido' => empty($erros), 'erros' => $erros];
}

function criarCategoria(PDO $pdo, array $dados): array
{
    $validacao = validarDadosCategoria($dados);

    if (!$validacao['valido']) {
        return ['sucesso' => false, 'mensagem' => implode(' ', $validacao['erros'])];
    }

    $nome = trim($dados['nome']);
    $descricao = trim($dados['descricao'] ?? '') ?: null;

    $stmt = $pdo->prepare('SELECT id FROM categorias WHERE nome = ? LIMIT 1');
    $stmt->execute([$nome]);

    if ($stmt->fetch()) {
        return ['sucesso' => false, 'mensagem' => 'Já existe uma categoria com este nome.'];
    }

    $pdo->prepare('INSERT INTO categorias (nome, descricao) VALUES (?, ?)')->execute([$nome, $descricao]);

    return ['sucesso' => true, 'mensagem' => 'Categoria cadastrada com sucesso.'];
}

function atualizarCategoria(PDO $pdo, int $id, array $dados): array
{
    if (buscarCategoriaPorId($pdo, $id) === null) {
        return ['sucesso' => false, 'mensagem' => 'Categoria não encontrada.'];
    }

    $validacao = validarDadosCategoria($dados);

    if (!$validacao['valido']) {
        return ['sucesso' => false, 'mensagem' => implode(' ', $validacao['erros'])];
    }

    $nome = trim($dados['nome']);
    $descricao = trim($dados['descricao'] ?? '') ?: null;

    $stmt = $pdo->prepare('SELECT id FROM categorias WHERE nome = ? AND id <> ? LIMIT 1');
    $stmt->execute([$nome, $id]);

    if ($stmt->fetch()) {
        return ['sucesso' => false, 'mensagem' => 'Já existe outra categoria com este nome.'];
    }

    $pdo->prepare('UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?')
        ->execute([$nome, $descricao, $id]);

    return ['sucesso' => true, 'mensagem' => 'Categoria atualizada com sucesso.'];
}

function excluirCategoria(PDO $pdo, int $id): array
{
    $categoria = buscarCategoriaPorId($pdo, $id);

    if ($categoria === null) {
        return ['sucesso' => false, 'mensagem' => 'Categoria não encontrada.'];
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM cookie_categoria WHERE categoria_id = ?');
    $stmt->execute([$id]);
    $qtdVinculos = (int) $stmt->fetchColumn();

    $pdo->prepare('DELETE FROM categorias WHERE id = ?')->execute([$id]);

    if ($qtdVinculos > 0) {
        return [
            'sucesso'  => true,
            'mensagem' => 'Categoria "' . $categoria['nome'] . '" excluída. ' . $qtdVinculos . ' vínculo(s) com cookies foram removidos.',
        ];
    }

    return ['sucesso' => true, 'mensagem' => 'Categoria excluída com sucesso.'];
}

function normalizarIdsCategorias(array $origem): array
{
    $ids = $origem['categorias_ids'] ?? $origem['categorias'] ?? [];

    if (!is_array($ids)) {
        return [];
    }

    return array_values(array_unique(array_map('intval', $ids)));
}
