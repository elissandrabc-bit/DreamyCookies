<?php
/**
 * Funções de negócio - Dreamy Cookies
 * Tech Forge: arrays estruturados, funções modulares, parâmetros e retorno.
 */

require_once __DIR__ . '/../config/database.php';

/**
 * Busca todos os cookies ativos com suas categorias em um único array.
 *
 * @param PDO $pdo
 * @return array
 */
function buscarCookies(PDO $pdo): array
{
    $sql = "
        SELECT
            c.id,
            c.nome,
            c.descricao,
            c.preco,
            c.imagem,
            GROUP_CONCAT(DISTINCT cat.id ORDER BY cat.id SEPARATOR ',') AS categorias_ids,
            GROUP_CONCAT(DISTINCT cat.nome ORDER BY cat.id SEPARATOR '|') AS categorias_nomes
        FROM cookies c
        LEFT JOIN cookie_categoria cc ON cc.cookie_id = c.id
        LEFT JOIN categorias cat ON cat.id = cc.categoria_id
        WHERE c.ativo = 1
        GROUP BY c.id, c.nome, c.descricao, c.preco, c.imagem
        ORDER BY c.preco ASC, c.nome ASC
    ";

    $stmt = $pdo->query($sql);
    $cookies = [];

    while ($row = $stmt->fetch()) {
        $cookies[] = [
            'id'              => (int) $row['id'],
            'nome'            => $row['nome'],
            'descricao'       => $row['descricao'],
            'preco'           => (float) $row['preco'],
            'imagem'          => $row['imagem'],
            'categorias_ids'  => $row['categorias_ids'] !== null
                ? array_map('intval', explode(',', $row['categorias_ids']))
                : [],
            'categorias_nomes'=> $row['categorias_nomes'] !== null
                ? explode('|', $row['categorias_nomes'])
                : [],
        ];
    }

    return $cookies;
}

/**
 * Busca todas as categorias em array.
 *
 * @param PDO $pdo
 * @return array
 */
function buscarCategorias(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT id, nome, descricao FROM categorias ORDER BY nome');
    $categorias = [];

    while ($row = $stmt->fetch()) {
        $categorias[] = [
            'id'        => (int) $row['id'],
            'nome'      => $row['nome'],
            'descricao' => $row['descricao'],
        ];
    }

    return $categorias;
}

/**
 * Filtra cookies por categoria (Tech Forge: filtro em array).
 *
 * @param array $cookies
 * @param int|null $categoriaId
 * @return array
 */
function filtrarCookiesPorCategoria(array $cookies, ?int $categoriaId): array
{
    if (!validarArrayCookies($cookies)) {
        return [];
    }

    if ($categoriaId === null || $categoriaId <= 0) {
        return $cookies;
    }

    $filtrados = [];

    foreach ($cookies as $cookie) {
        if (in_array($categoriaId, $cookie['categorias_ids'], true)) {
            $filtrados[] = $cookie;
        }
    }

    return $filtrados;
}

/**
 * Filtra cookies por termo de busca no nome ou descrição.
 *
 * @param array $cookies
 * @param string $termo
 * @return array
 */
function filtrarCookiesPorBusca(array $cookies, string $termo): array
{
    if (!validarArrayCookies($cookies)) {
        return [];
    }

    $termo = trim(mb_strtolower($termo));

    if ($termo === '') {
        return $cookies;
    }

    $filtrados = [];

    foreach ($cookies as $cookie) {
        $nome = mb_strtolower($cookie['nome']);
        $descricao = mb_strtolower($cookie['descricao']);

        if (str_contains($nome, $termo) || str_contains($descricao, $termo)) {
            $filtrados[] = $cookie;
        }
    }

    return $filtrados;
}

/**
 * Valida se o array de cookies é válido para processamento.
 *
 * @param array $cookies
 * @return bool
 */
function validarArrayCookies(array $cookies): bool
{
    if (empty($cookies)) {
        return false;
    }

    foreach ($cookies as $cookie) {
        if (!isset($cookie['id'], $cookie['nome'], $cookie['preco'])) {
            return false;
        }

        if ($cookie['preco'] < 0) {
            return false;
        }
    }

    return true;
}

/**
 * Formata preço em Real brasileiro.
 *
 * @param float $preco
 * @return string
 */
function formatarPreco(float $preco): string
{
    if ($preco < 0) {
        return 'R$ 0,00';
    }

    return 'R$ ' . number_format($preco, 2, ',', '.');
}

/**
 * Calcula total de um pedido a partir de itens.
 *
 * @param array $itens Array de ['preco' => float, 'quantidade' => int]
 * @return float
 */
function calcularTotalPedido(array $itens): float
{
    if (empty($itens)) {
        return 0.0;
    }

    $total = 0.0;

    foreach ($itens as $item) {
        if (!isset($item['preco'], $item['quantidade'])) {
            continue;
        }

        if ($item['preco'] < 0 || $item['quantidade'] <= 0) {
            continue;
        }

        $total += $item['preco'] * $item['quantidade'];
    }

    return round($total, 2);
}

/**
 * Retorna quantidade total de itens no carrinho (soma das quantidades).
 *
 * @return int
 */
function quantidadeItensCarrinho(): int
{
    iniciarSessao();
    $carrinho = $_SESSION['carrinho'] ?? [];
    $total = 0;

    foreach ($carrinho as $quantidade) {
        $total += max(0, (int) $quantidade);
    }

    return $total;
}

/**
 * Busca um cookie ativo pelo ID.
 *
 * @param PDO $pdo
 * @param int $id
 * @return array|null
 */
function buscarCookiePorId(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, nome, descricao, preco, imagem FROM cookies WHERE id = ? AND ativo = 1 LIMIT 1'
    );
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row) {
        return null;
    }

    return [
        'id'        => (int) $row['id'],
        'nome'      => $row['nome'],
        'descricao' => $row['descricao'],
        'preco'     => (float) $row['preco'],
        'imagem'    => $row['imagem'],
    ];
}

/**
 * Adiciona cookie ao carrinho (sessão).
 *
 * @param PDO $pdo
 * @param int $cookieId
 * @param int $quantidade
 * @return array ['sucesso' => bool, 'mensagem' => string]
 */
function adicionarAoCarrinho(PDO $pdo, int $cookieId, int $quantidade = 1): array
{
    if ($cookieId <= 0 || $quantidade <= 0) {
        return ['sucesso' => false, 'mensagem' => 'Quantidade inválida.'];
    }

    if ($quantidade > 20) {
        return ['sucesso' => false, 'mensagem' => 'Quantidade máxima por item: 20 unidades.'];
    }

    $cookie = buscarCookiePorId($pdo, $cookieId);

    if (!$cookie) {
        return ['sucesso' => false, 'mensagem' => 'Cookie não encontrado.'];
    }

    iniciarSessao();

    if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    $atual = (int) ($_SESSION['carrinho'][$cookieId] ?? 0);
    $_SESSION['carrinho'][$cookieId] = min(20, $atual + $quantidade);

    return [
        'sucesso'  => true,
        'mensagem' => $cookie['nome'] . ' adicionado ao carrinho!',
    ];
}

/**
 * Atualiza quantidade de um item no carrinho.
 *
 * @param int $cookieId
 * @param int $quantidade
 * @return bool
 */
function atualizarQuantidadeCarrinho(int $cookieId, int $quantidade): bool
{
    iniciarSessao();

    if (!isset($_SESSION['carrinho'][$cookieId])) {
        return false;
    }

    if ($quantidade <= 0) {
        unset($_SESSION['carrinho'][$cookieId]);
        return true;
    }

    $_SESSION['carrinho'][$cookieId] = min(20, $quantidade);

    return true;
}

/**
 * Remove item do carrinho.
 *
 * @param int $cookieId
 */
function removerDoCarrinho(int $cookieId): void
{
    iniciarSessao();
    unset($_SESSION['carrinho'][$cookieId]);
}

/**
 * Esvazia o carrinho.
 */
function limparCarrinho(): void
{
    iniciarSessao();
    $_SESSION['carrinho'] = [];
}

/**
 * Monta itens do carrinho com dados do banco (Tech Forge: array estruturado).
 *
 * @param PDO $pdo
 * @return array
 */
function montarItensCarrinho(PDO $pdo): array
{
    iniciarSessao();
    $carrinho = $_SESSION['carrinho'] ?? [];

    if (empty($carrinho)) {
        return [];
    }

    $itens = [];

    foreach ($carrinho as $cookieId => $quantidade) {
        $cookieId = (int) $cookieId;
        $quantidade = (int) $quantidade;

        if ($quantidade <= 0) {
            continue;
        }

        $cookie = buscarCookiePorId($pdo, $cookieId);

        if (!$cookie) {
            removerDoCarrinho($cookieId);
            continue;
        }

        $subtotal = round($cookie['preco'] * $quantidade, 2);

        $itens[] = [
            'id'         => $cookie['id'],
            'nome'       => $cookie['nome'],
            'preco'      => $cookie['preco'],
            'imagem'     => $cookie['imagem'],
            'quantidade' => $quantidade,
            'subtotal'   => $subtotal,
        ];
    }

    return $itens;
}

/**
 * Calcula total do carrinho usando calcularTotalPedido().
 *
 * @param array $itens
 * @return float
 */
function calcularTotalCarrinho(array $itens): float
{
    $paraCalculo = [];

    foreach ($itens as $item) {
        $paraCalculo[] = [
            'preco'      => $item['preco'],
            'quantidade' => $item['quantidade'],
        ];
    }

    return calcularTotalPedido($paraCalculo);
}

/**
 * Gera link WhatsApp com resumo do carrinho e total.
 *
 * @param array $itens
 * @param float $total
 * @return string
 */
function gerarLinkWhatsAppCarrinho(array $itens, float $total): string
{
    $numero = '5544997464801';
    $linhas = ['Olá! Gostaria de fazer um pedido na Dreamy Cookies:', ''];

    foreach ($itens as $item) {
        $linhas[] = sprintf(
            '• %s x%d — %s',
            $item['nome'],
            $item['quantidade'],
            formatarPreco($item['subtotal'])
        );
    }

    $linhas[] = '';
    $linhas[] = '*Total: ' . formatarPreco($total) . '*';

    return 'https://wa.me/' . $numero . '?text=' . rawurlencode(implode("\n", $linhas));
}

/**
 * Consome mensagem flash da sessão (ex.: após adicionar ao carrinho).
 *
 * @param string $tipo sucesso|erro
 * @return string
 */
function consumirFlash(string $tipo): string
{
    iniciarSessao();
    $chave = 'flash_' . $tipo;
    $mensagem = $_SESSION[$chave] ?? '';
    unset($_SESSION[$chave]);

    return is_string($mensagem) ? $mensagem : '';
}

/**
 * Valida URL de retorno interna após ação no carrinho.
 *
 * @param string $url
 * @return string
 */
function urlRetornoCarrinho(string $url): string
{
    $url = trim($url);

    if ($url === '' || !str_starts_with($url, 'index.php')) {
        return 'index.php';
    }

    return $url;
}

/**
 * Valida dados de cadastro de cliente.
 *
 * @param array $dados
 * @return array ['valido' => bool, 'erros' => string[]]
 */
function validarCadastroCliente(array $dados): array
{
    $erros = [];

    $nome = trim($dados['nome'] ?? '');
    $email = trim($dados['email'] ?? '');
    $telefone = trim($dados['telefone'] ?? '');
    $senha = $dados['senha'] ?? '';
    $confirmar = $dados['confirmar_senha'] ?? '';

    if ($nome === '' || mb_strlen($nome) < 3) {
        $erros[] = 'Informe um nome com pelo menos 3 caracteres.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    if ($telefone === '' || mb_strlen(preg_replace('/\D/', '', $telefone)) < 10) {
        $erros[] = 'Informe um telefone válido com DDD.';
    }

    if (mb_strlen($senha) < 6) {
        $erros[] = 'A senha deve ter no mínimo 6 caracteres.';
    }

    if ($senha !== $confirmar) {
        $erros[] = 'As senhas não coincidem.';
    }

    return [
        'valido' => empty($erros),
        'erros'  => $erros,
    ];
}

/**
 * Cadastra um novo cliente no banco.
 *
 * @param PDO $pdo
 * @param array $dados
 * @return array ['sucesso' => bool, 'mensagem' => string]
 */
function cadastrarCliente(PDO $pdo, array $dados): array
{
    $validacao = validarCadastroCliente($dados);

    if (!$validacao['valido']) {
        return [
            'sucesso'  => false,
            'mensagem' => implode(' ', $validacao['erros']),
        ];
    }

    $stmt = $pdo->prepare('SELECT id FROM clientes WHERE email = ? LIMIT 1');
    $stmt->execute([trim($dados['email'])]);

    if ($stmt->fetch()) {
        return [
            'sucesso'  => false,
            'mensagem' => 'Este e-mail já está cadastrado.',
        ];
    }

    $sql = 'INSERT INTO clientes (nome, email, telefone, senha_hash) VALUES (?, ?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        trim($dados['nome']),
        trim($dados['email']),
        trim($dados['telefone']),
        password_hash($dados['senha'], PASSWORD_DEFAULT),
    ]);

    return [
        'sucesso'  => true,
        'mensagem' => 'Cadastro realizado com sucesso! Faça login para continuar.',
    ];
}

/**
 * Autentica cliente por e-mail e senha.
 *
 * @param PDO $pdo
 * @param string $email
 * @param string $senha
 * @return array|null Dados do cliente ou null
 */
function autenticarCliente(PDO $pdo, string $email, string $senha): ?array
{
    $stmt = $pdo->prepare('SELECT id, nome, email, telefone, senha_hash FROM clientes WHERE email = ? LIMIT 1');
    $stmt->execute([trim($email)]);
    $cliente = $stmt->fetch();

    if (!$cliente || !password_verify($senha, $cliente['senha_hash'])) {
        return null;
    }

    unset($cliente['senha_hash']);

    return $cliente;
}

/**
 * Monta URL da foto da criadora com fallback.
 *
 * @param string $arquivo
 * @return string
 */
function urlImagemCriadora(string $arquivo): string
{
    $caminho = 'assets/img/criadora/' . $arquivo;

    if (file_exists(__DIR__ . '/../' . $caminho)) {
        return $caminho;
    }

    return 'assets/img/cookie-placeholder.svg';
}

/**
 * Monta URL da imagem do cookie com fallback.
 *
 * @param string $arquivo
 * @return string
 */
function urlImagemCookie(string $arquivo): string
{
    $caminho = 'assets/img/cookies/' . $arquivo;

    if (file_exists(__DIR__ . '/../' . $caminho)) {
        return $caminho;
    }

    return 'assets/img/cookie-placeholder.svg';
}

/**
 * Gera link do WhatsApp para pedido.
 *
 * @param string $nomeCookie
 * @param float $preco
 * @return string
 */
function gerarLinkWhatsApp(string $nomeCookie, float $preco): string
{
    $numero = '5544997464801';
    $mensagem = sprintf(
        'Olá! Gostaria de pedir o cookie *%s* (%s).',
        $nomeCookie,
        formatarPreco($preco)
    );

    return 'https://wa.me/' . $numero . '?text=' . rawurlencode($mensagem);
}

/**
 * Retorna URL do WhatsApp geral da loja.
 *
 * @return string
 */
function linkWhatsAppLoja(): string
{
    return 'https://wa.me/5544997464801?text=' . rawurlencode('Olá! Gostaria de fazer um pedido na Dreamy Cookies.');
}

/**
 * Inicia sessão se ainda não iniciada.
 */
function iniciarSessao(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Verifica se cliente está logado.
 *
 * @return bool
 */
function clienteLogado(): bool
{
    iniciarSessao();
    return isset($_SESSION['cliente_id']);
}

/**
 * Retorna nome do cliente logado ou null.
 *
 * @return string|null
 */
function nomeClienteLogado(): ?string
{
    iniciarSessao();
    return $_SESSION['cliente_nome'] ?? null;
}

/**
 * Busca todos os clientes cadastrados (sem senha).
 *
 * @param PDO $pdo
 * @return array
 */
function buscarClientes(PDO $pdo): array
{
    $stmt = $pdo->query(
        'SELECT id, nome, email, telefone, data_cadastro
         FROM clientes
         ORDER BY data_cadastro DESC'
    );

    $clientes = [];

    while ($row = $stmt->fetch()) {
        $clientes[] = [
            'id'             => (int) $row['id'],
            'nome'           => $row['nome'],
            'email'          => $row['email'],
            'telefone'       => $row['telefone'],
            'data_cadastro'  => $row['data_cadastro'],
        ];
    }

    return $clientes;
}

/**
 * Verifica se o administrador está autenticado.
 *
 * @return bool
 */
function adminLogado(): bool
{
    iniciarSessao();
    return !empty($_SESSION['admin_logado']);
}

/**
 * Autentica o administrador pela senha configurada.
 *
 * @param string $senha
 * @return bool
 */
function autenticarAdmin(string $senha): bool
{
    $config = require __DIR__ . '/../config/admin.php';

    if ($senha !== $config['senha']) {
        return false;
    }

    iniciarSessao();
    $_SESSION['admin_logado'] = true;

    return true;
}

/**
 * Encerra sessão administrativa.
 */
function sairAdmin(): void
{
    iniciarSessao();
    unset($_SESSION['admin_logado']);
}

/**
 * Formata data/hora de cadastro para exibição.
 *
 * @param string $dataHora
 * @return string
 */
function formatarDataCadastro(string $dataHora): string
{
    $data = DateTime::createFromFormat('Y-m-d H:i:s', $dataHora);

    if (!$data) {
        return $dataHora;
    }

    return $data->format('d/m/Y H:i');
}
