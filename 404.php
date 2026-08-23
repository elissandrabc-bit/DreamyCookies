<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/template.php';

http_response_code(404);

$titulo = 'Página não encontrada - Dreamy Cookies';

renderizarPagina('404', compact('titulo'));
