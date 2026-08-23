<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

$titulo = 'Dashboard - Dreamy Cookies';

renderizarPagina('dashboard', compact('titulo'));
