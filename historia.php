<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/template.php';

iniciarSessao();

$historia = require __DIR__ . '/config/historia.php';

$titulo = 'Nossa História - Dreamy Cookies';

renderizarPagina('historia', compact('titulo', 'historia'));
