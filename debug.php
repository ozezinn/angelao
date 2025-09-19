<?php
echo "<pre style='font-family: monospace; font-size: 14px; background: #f4f4f4; padding: 20px; border: 1px solid #ccc;'>";
echo "<h1>Variáveis de Ambiente Visíveis para o PHP</h1>";

echo "<h2>--- Conteúdo de getenv() ---</h2>";
// getenv() pode não ser populado por padrão, mas verificamos
print_r(getenv());

echo "\n<h2>--- Conteúdo de \$_SERVER ---</h2>";
print_r($_SERVER);

echo "\n<h2>--- Conteúdo de \$_ENV ---</h2>";
print_r($_ENV);

echo "</pre>";
?>