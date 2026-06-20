<?php
/**
 * Sistema de templates PHP - Dreamy Cookies
 */

/**
 * Renderiza um template com variáveis.
 *
 * @param string $template Nome do arquivo em /templates (sem .php)
 * @param array $dados Variáveis disponíveis no template
 */
function renderizar(string $template, array $dados = []): void
{
    extract($dados, EXTR_SKIP);
    require __DIR__ . '/../templates/' . $template . '.php';
}

/**
 * Renderiza página completa (layout + conteúdo).
 *
 * @param string $templateConteudo
 * @param array $dados
 */
function renderizarPagina(string $templateConteudo, array $dados = []): void
{
    $dados['conteudo_template'] = $templateConteudo;
    renderizar('layout', $dados);
}
